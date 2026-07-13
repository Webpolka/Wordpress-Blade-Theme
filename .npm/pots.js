import fs from 'fs';
import path from 'path';
import gettextParser from 'gettext-parser';

const domain = 'weblegko';
const themeDir = './';
const langDir = './languages';
const languages = ['ru_RU', 'en_US'];

if (!fs.existsSync(langDir)) fs.mkdirSync(langDir, { recursive: true });

// Директории, где мы ищем переводы в Sage
const scanDirs = ['app', 'resources/views', 'resources/functions'];
const excludeDirs = ['node_modules', 'vendor', 'dist', 'storage'];

// Регулярка для поиска __('Текст', 'weblegko') или __("Текст", "weblegko")
// Захватывает: __, _e, esc_html__, esc_attr__, esc_html_e
const translationRegex = /(?:__|_e|esc_html__|esc_attr__|esc_html_e)\(\s*['"`](.*?)['"`]\s*,\s*['"`]weblegko['"`]/g;

// Рекурсивная функция для получения всех файлов
function getAllFiles(dirPath, arrayOfFiles) {
  const files = fs.readdirSync(dirPath);
  arrayOfFiles = arrayOfFiles || [];

  files.forEach(function(file) {
    const fullPath = path.join(dirPath, file);
    if (fs.statSync(fullPath).isDirectory()) {
      if (!excludeDirs.includes(file)) {
        arrayOfFiles = getAllFiles(fullPath, arrayOfFiles);
      }
    } else {
      if (file.endsWith('.php') || file.endsWith('.blade.php')) {
        arrayOfFiles.push(fullPath);
      }
    }
  });

  return arrayOfFiles;
}

console.log('🔍 Сканирую файлы темы Sage (без WP-CLI)...');

let allFiles = [];
scanDirs.forEach(dir => {
  if (fs.existsSync(dir)) {
    allFiles = allFiles.concat(getAllFiles(dir));
  }
});

const translations = {
  "": {
    "": {
      msgid: "",
      msgstr: [
        "Content-Type: text/plain; charset=UTF-8\n"
      ]
    }
  }
};

let count = 0;

// Ищем переводы
allFiles.forEach(filePath => {
  const content = fs.readFileSync(filePath, 'utf8');
  let match;
  while ((match = translationRegex.exec(content)) !== null) {
    const msgid = match[1];
    // Если перевода еще нет в объекте, добавляем
    if (!translations[""][msgid]) {
      translations[""][msgid] = {
        msgid: msgid,
        msgstr: ""
      };
      count++;
    }
  }
});

console.log(`✅ Найдено строк для перевода: ${count}`);

const potFile = path.join(langDir, `${domain}.pot`);

// Создаем объект POT
const potData = {
  charset: 'utf-8',
  headers: {
    'Project-Id-Version': 'Weblegko Theme 1.0.0',
    'Report-Msgid-Bugs-To': 'https://weblegko.ru/support',
    'Last-Translator': 'Weblegko Team <dev@weblegko.ru>',
    'Language-Team': 'Weblegko Team <dev@weblegko.ru>',
    'MIME-Version': '1.0',
    'Content-Type': 'text/plain; charset=UTF-8',
    'Content-Transfer-Encoding': '8bit',
    'Plural-Forms': 'nplurals=2; plural=(n != 1);',
    'X-Domain': 'weblegko'
  },
  translations: translations
};

fs.writeFileSync(potFile, gettextParser.po.compile(potData));
console.log(`✅ POT файл создан: ${potFile}`);

// Генерация PO и MO для каждого языка
languages.forEach(lang => {
  const poPath = path.join(langDir, `${lang}.po`);
  const moPath = path.join(langDir, `${lang}.mo`);

  let po;
  if (fs.existsSync(poPath)) {
    // Если PO уже есть, сохраняем старые переводы
    po = gettextParser.po.parse(fs.readFileSync(poPath));
    const oldTranslations = po.translations;
    po.translations = translations;
    
    // Возвращаем старые переводы (msgstr), если строка совпадает
    for (const ctx in oldTranslations) {
      for (const msgid in oldTranslations[ctx]) {
        if (po.translations[ctx] && po.translations[ctx][msgid]) {
          po.translations[ctx][msgid].msgstr = oldTranslations[ctx][msgid].msgstr;
        }
      }
    }
  } else {
    // Если PO нет, создаем пустой из POT
    po = gettextParser.po.parse(gettextParser.po.compile(potData));
  }

  po.headers.Language = lang;
  po.headers['Content-Type'] = 'text/plain; charset=UTF-8';
  po.headers['X-Domain'] = domain;

  fs.writeFileSync(poPath, gettextParser.po.compile(po));
  fs.writeFileSync(moPath, gettextParser.mo.compile(po));

  console.log(`✅ ${lang} PO и MO шаблоны созданы/обновлены`);
});

console.log('🎉 Все шаблоны для переводов готовы!');