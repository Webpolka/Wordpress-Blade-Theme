#!/usr/bin/env node

import { Command } from 'commander';
import chalk from 'chalk';
import fs from 'fs-extra';
import path from 'path';
import ora from 'ora';
import inquirer from 'inquirer';
import { fileURLToPath } from 'url';
import crypto from 'crypto';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const program = new Command();

// Пути
const TEMPLATES_DIR = path.join(__dirname, 'templates', 'components');
const META_FILE = path.join(__dirname, 'templates', 'meta.json');
const DEST_DIR = path.join(process.cwd(), 'resources', 'views', 'components');

// Игнорируемые файлы
const IGNORED_FILES = [
  '.DS_Store',
  'Thumbs.db',
  '.gitkeep',
  '.gitignore',
  '.editorconfig',
];

// Манифест
const MANIFEST_PATH = path.join(
  process.cwd(),
  'resources',
  'components-manifest.json',
);
const PUBLIC_MANIFEST_PATH = path.join(
  process.cwd(),
  'public',
  'components-manifest.json',
);

// Гарантируем, что манифест существует (даже если он пустой)
function ensureManifest() {
  if (!fs.existsSync(MANIFEST_PATH)) {
    const emptyManifest = { components: [], version: 'init' };
    fs.writeJsonSync(MANIFEST_PATH, emptyManifest, { spaces: 2 });
    fs.ensureDirSync(path.dirname(PUBLIC_MANIFEST_PATH));
    fs.copySync(MANIFEST_PATH, PUBLIC_MANIFEST_PATH);
    console.log(chalk.gray('Создан пустой манифест компонентов.'));
  }
}

// ------------------ Вспомогательные функции ------------------

function isIgnoredFile(filename) {
  return IGNORED_FILES.includes(filename) || filename.startsWith('.');
}

// Проверка, является ли папка компонентом (содержит index.blade.php)
function isComponentDir(dirPath) {
  return fs.existsSync(path.join(dirPath, 'index.blade.php'));
}

// Сканирует шаблоны и возвращает массив имён компонентов (с поддержкой вложенности)
function getComponentsList(baseDir) {
  const result = [];

  // 1. Сканируем файлы .blade.php в корне (плоские компоненты)
  const items = fs.readdirSync(baseDir);
  for (const item of items) {
    if (isIgnoredFile(item)) continue;
    const fullPath = path.join(baseDir, item);
    const stat = fs.statSync(fullPath);
    if (stat.isFile() && item.endsWith('.blade.php')) {
      const name = item.replace(/\.blade\.php$/, '');
      result.push(name);
    }
  }

  // 2. Сканируем папки с index.blade.php (сложные компоненты) – рекурсивно
  function walkDirs(dir, relative = '') {
    const entries = fs.readdirSync(dir);
    for (const entry of entries) {
      if (isIgnoredFile(entry)) continue;
      const fullPath = path.join(dir, entry);
      if (fs.statSync(fullPath).isDirectory()) {
        const indexPath = path.join(fullPath, 'index.blade.php');
        if (fs.existsSync(indexPath)) {
          // Это компонент-папка
          const name = relative ? `${relative}/${entry}` : entry;
          result.push(name);
        } else {
          // Идём глубже (если папка без index, возможно, вложенные компоненты)
          walkDirs(fullPath, relative ? `${relative}/${entry}` : entry);
        }
      }
    }
  }
  walkDirs(baseDir);

  // Убираем дубли
  return [...new Set(result)];
}

// Проверяет существование и определяет тип
function componentExistsInTemplates(component) {
  const filePath = path.join(TEMPLATES_DIR, `${component}.blade.php`);
  if (fs.existsSync(filePath)) {
    return { exists: true, type: 'file' };
  }
  const dirPath = path.join(TEMPLATES_DIR, component);
  if (fs.existsSync(dirPath) && isComponentDir(dirPath)) {
    return { exists: true, type: 'dir' };
  }
  return false;
}

// Обновление манифеста
function updateManifest(component, action, type = null) {
  let manifest = { components: [] };
  if (fs.existsSync(MANIFEST_PATH)) {
    manifest = fs.readJsonSync(MANIFEST_PATH);
  }
  if (action === 'add') {
    const exists = manifest.components.some((c) => c.name === component);
    if (!exists) {
      manifest.components.push({ name: component, type });
    }
  } else if (action === 'remove') {
    manifest.components = manifest.components.filter(
      (c) => c.name !== component,
    );
  }
  const content = JSON.stringify(manifest);
  const hash = crypto
    .createHash('sha1')
    .update(content)
    .digest('hex')
    .slice(0, 8);
  manifest.version = hash;

  fs.writeJsonSync(MANIFEST_PATH, manifest, { spaces: 2 });
  fs.ensureDirSync(path.dirname(PUBLIC_MANIFEST_PATH));
  fs.copySync(MANIFEST_PATH, PUBLIC_MANIFEST_PATH);
}

// Очистка пустой папки
async function cleanupDirIfEmpty(dirPath) {
  if (!fs.existsSync(dirPath)) return;
  const files = fs.readdirSync(dirPath);
  const nonIgnored = files.filter((f) => !isIgnoredFile(f));
  if (nonIgnored.length === 0) {
    await fs.remove(dirPath);
    console.log(
      chalk.gray(
        `Папка ${dirPath} удалена (содержала только служебные файлы).`,
      ),
    );
  }
}

// Вывод документации
function printDocs(componentPath, meta) {
  if (!meta || !meta[componentPath]) return;
  const info = meta[componentPath];
  console.log('\n' + chalk.bgCyan.black.bold(` DOCS: ${componentPath} `));
  console.log(chalk.white(`  ${info.description}`));
  if (info.props)
    console.log(chalk.yellow('  Props: ') + chalk.gray(info.props));
  console.log(chalk.green('  Usage:'));
  console.log(chalk.gray(`    ${info.usage}`));
  console.log(chalk.white(''));
}

// ------------------ Команды ------------------

// Команда: инициализация манифеста
program
  .command('init')
  .description('Создать пустой манифест компонентов')
  .action(() => {
    ensureManifest();
    console.log(chalk.green('✅ Манифест создан.'));
  });

// add <component>
program
  .command('add <component>')
  .description(
    'Добавить UI компонент (поддерживаются плоские файлы и папки с index.blade.php)',
  )
  .action(async (component) => {
    const spinner = ora(
      `Генерирую компонент ${chalk.cyan(component)}...`,
    ).start();

    const info = componentExistsInTemplates(component);
    if (!info) {
      spinner.fail(`Компонент ${chalk.red(component)} не найден в библиотеке!`);
      console.log(chalk.yellow('\nДоступные компоненты:'));
      const available = getComponentsList(TEMPLATES_DIR);
      console.log(chalk.white(available.join(', ')));
      process.exit(1);
    }

    let src, dest;
    if (info.type === 'file') {
      src = path.join(TEMPLATES_DIR, `${component}.blade.php`);
      dest = path.join(DEST_DIR, `${component}.blade.php`);
    } else {
      src = path.join(TEMPLATES_DIR, component);
      dest = path.join(DEST_DIR, component);
    }

    if (fs.existsSync(dest)) {
      spinner.warn(
        `Компонент ${chalk.yellow(component)} уже существует в теме! Пропускаем.`,
      );
      return;
    }

    try {
      await fs.ensureDir(path.dirname(dest));
      await fs.copy(src, dest);
      spinner.succeed(`Компонент ${chalk.green(component)} успешно добавлен!`);
      updateManifest(component, 'add', info.type);

      if (fs.existsSync(META_FILE)) {
        const meta = fs.readJsonSync(META_FILE);
        printDocs(component, meta);
      }
    } catch (err) {
      spinner.fail('Ошибка при копировании');
      console.error(err);
    }
  });

// install (интерактивная установка)
program
  .command('install')
  .description('Интерактивный выбор компонентов для установки')
  .action(async () => {
    if (!fs.existsSync(TEMPLATES_DIR)) {
      console.log(chalk.red('Папка с шаблонами не найдена!'));
      process.exit(1);
    }

    const available = getComponentsList(TEMPLATES_DIR);
    if (available.length === 0) {
      console.log(chalk.yellow('Нет доступных компонентов для установки.'));
      return;
    }

    const answers = await inquirer.prompt([
      {
        type: 'checkbox',
        name: 'selectedComponents',
        message: 'Какие компоненты установить?',
        choices: available,
        pageSize: 20,
      },
    ]);

    if (answers.selectedComponents.length === 0) {
      console.log(chalk.gray('Ничего не выбрано. Выход.'));
      return;
    }

    let meta = {};
    if (fs.existsSync(META_FILE)) {
      meta = fs.readJsonSync(META_FILE);
    }

    await fs.ensureDir(DEST_DIR);

    for (const comp of answers.selectedComponents) {
      const info = componentExistsInTemplates(comp);
      if (!info) {
        console.log(chalk.red('✗') + ` ${comp} не найден в шаблонах, пропуск`);
        continue;
      }
      let src, dest;
      if (info.type === 'file') {
        src = path.join(TEMPLATES_DIR, `${comp}.blade.php`);
        dest = path.join(DEST_DIR, `${comp}.blade.php`);
      } else {
        src = path.join(TEMPLATES_DIR, comp);
        dest = path.join(DEST_DIR, comp);
      }

      if (!fs.existsSync(dest)) {
        await fs.copy(src, dest);
        console.log(chalk.green('✓') + ` ${comp} установлен`);
        updateManifest(comp, 'add', info.type);
        printDocs(comp, meta);
      } else {
        console.log(chalk.yellow('⚠') + ` ${comp} уже существует, пропущен`);
      }
    }

    console.log(chalk.green.bold('\nГотово! Компоненты подключены.'));
  });

// remove <component>
program
  .command('remove <component>')
  .alias('rm')
  .description('Удалить установленный компонент (файл или папку)')
  .action(async (component) => {
    const filePath = path.join(DEST_DIR, `${component}.blade.php`);
    const dirPath = path.join(DEST_DIR, component);

    let target = null;
    if (fs.existsSync(filePath)) target = filePath;
    else if (fs.existsSync(dirPath) && isComponentDir(dirPath))
      target = dirPath;

    if (!target) {
      console.log(
        chalk.yellow(`⚠ Компонент ${chalk.cyan(component)} не найден в теме.`),
      );
      return;
    }

    const spinner = ora(`Удаляю ${chalk.cyan(component)}...`).start();
    try {
      await fs.remove(target);
      spinner.succeed(`Компонент ${chalk.green(component)} удалён.`);
      // При удалении тип не нужен, передаём null
      updateManifest(component, 'remove');

      let parent = path.dirname(target);
      while (parent !== DEST_DIR && parent.startsWith(DEST_DIR)) {
        await cleanupDirIfEmpty(parent);
        parent = path.dirname(parent);
      }
    } catch (err) {
      spinner.fail('Ошибка при удалении');
      console.error(err);
    }
  });

// uninstall (интерактивное удаление)
program
  .command('uninstall')
  .description('Интерактивный выбор компонентов для удаления')
  .action(async () => {
    if (!fs.existsSync(DEST_DIR)) {
      console.log(
        chalk.yellow('Папка назначения не существует. Нечего удалять.'),
      );
      return;
    }

    const installed = [];

    function walk(dir, relative = '') {
      const entries = fs.readdirSync(dir);
      for (const entry of entries) {
        if (isIgnoredFile(entry)) continue;
        const fullPath = path.join(dir, entry);
        const stat = fs.statSync(fullPath);

        if (stat.isFile() && entry.endsWith('.blade.php')) {
          const name = entry.replace(/\.blade\.php$/, '');
          installed.push(relative ? `${relative}/${name}` : name);
        } else if (stat.isDirectory()) {
          if (isComponentDir(fullPath)) {
            const name = relative ? `${relative}/${entry}` : entry;
            installed.push(name);
          } else {
            walk(fullPath, relative ? `${relative}/${entry}` : entry);
          }
        }
      }
    }

    walk(DEST_DIR);
    const uniqueInstalled = [...new Set(installed)];

    if (uniqueInstalled.length === 0) {
      console.log(chalk.gray('Нет установленных компонентов.'));
      return;
    }

    const answers = await inquirer.prompt([
      {
        type: 'checkbox',
        name: 'selected',
        message: 'Какие компоненты удалить?',
        choices: uniqueInstalled,
        pageSize: 20,
      },
    ]);

    if (answers.selected.length === 0) {
      console.log(chalk.gray('Ничего не выбрано. Выход.'));
      return;
    }

    for (const comp of answers.selected) {
      const filePath = path.join(DEST_DIR, `${comp}.blade.php`);
      const dirPath = path.join(DEST_DIR, comp);

      let target = null;
      if (fs.existsSync(filePath)) {
        target = filePath;
      } else if (fs.existsSync(dirPath) && isComponentDir(dirPath)) {
        target = dirPath;
      }

      if (target) {
        await fs.remove(target);
        console.log(chalk.red('✗') + ` ${comp} удалён`);
        updateManifest(comp, 'remove');

        let parent = path.dirname(target);
        while (parent !== DEST_DIR && parent.startsWith(DEST_DIR)) {
          await cleanupDirIfEmpty(parent);
          parent = path.dirname(parent);
        }
      } else {
        console.log(chalk.yellow('⚠') + ` ${comp} уже отсутствует`);
      }
    }

    console.log(chalk.green.bold('\nГотово! Удаление завершено.'));
  });

// remove-all
program
  .command('remove-all')
  .description('Удалить ВСЕ установленные компоненты (с подтверждением)')
  .action(async () => {
    if (!fs.existsSync(DEST_DIR)) {
      console.log(
        chalk.yellow('Папка назначения не существует. Нечего удалять.'),
      );
      return;
    }

    const installed = [];

    function walk(dir, relative = '') {
      const entries = fs.readdirSync(dir);
      for (const entry of entries) {
        if (isIgnoredFile(entry)) continue;
        const fullPath = path.join(dir, entry);
        const stat = fs.statSync(fullPath);

        if (stat.isFile() && entry.endsWith('.blade.php')) {
          const name = entry.replace(/\.blade\.php$/, '');
          installed.push(relative ? `${relative}/${name}` : name);
        } else if (stat.isDirectory()) {
          if (isComponentDir(fullPath)) {
            const name = relative ? `${relative}/${entry}` : entry;
            installed.push(name);
          } else {
            walk(fullPath, relative ? `${relative}/${entry}` : entry);
          }
        }
      }
    }

    walk(DEST_DIR);
    const uniqueInstalled = [...new Set(installed)];

    if (uniqueInstalled.length === 0) {
      console.log(chalk.gray('Нет установленных компонентов для удаления.'));
      return;
    }

    const { confirm } = await inquirer.prompt([
      {
        type: 'confirm',
        name: 'confirm',
        message: `Ты действительно хочешь удалить ВСЕ установленные компоненты (${uniqueInstalled.length} шт.)?`,
        default: false,
      },
    ]);

    if (!confirm) {
      console.log(chalk.gray('Операция отменена.'));
      return;
    }

    const spinner = ora('Удаляю все компоненты...').start();
    try {
      for (const comp of uniqueInstalled) {
        const filePath = path.join(DEST_DIR, `${comp}.blade.php`);
        const dirPath = path.join(DEST_DIR, comp);

        let target = null;
        if (fs.existsSync(filePath)) {
          target = filePath;
        } else if (fs.existsSync(dirPath) && isComponentDir(dirPath)) {
          target = dirPath;
        }

        if (target) {
          await fs.remove(target);
          console.log(chalk.red('✗') + ` ${comp} удалён`);
          updateManifest(comp, 'remove');

          let parent = path.dirname(target);
          while (parent !== DEST_DIR && parent.startsWith(DEST_DIR)) {
            await cleanupDirIfEmpty(parent);
            parent = path.dirname(parent);
          }
        } else {
          console.log(chalk.yellow('⚠') + ` ${comp} не найден, пропуск`);
        }
      }

      await cleanupDirIfEmpty(DEST_DIR);
      spinner.succeed(
        `Удалено ${chalk.green(uniqueInstalled.length)} компонентов.`,
      );
    } catch (err) {
      spinner.fail('Ошибка при удалении');
      console.error(err);
    }
  });

// Убедимся, что манифест существует перед запуском команд
ensureManifest();
program.parse();
