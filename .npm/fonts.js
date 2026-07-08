import fs from 'fs-extra';
import path from 'path';
import { Font } from 'fonteditor-core';
import ttf2woff2 from 'ttf2woff2';

import { fileURLToPath } from 'url';
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const fontsDir = path.join(__dirname, '../resources/fonts');
const woffDir = path.join(fontsDir, 'WOFF2');
const fontFacesFile = path.join(__dirname, '../resources/css/fonts.css');

const italicRegex = /italic/i;

// Шаблон @font-face
const fontFaceTemplate = (name, file, weight, style) => `@font-face {
  font-family: "${name}";
  src: url("../fonts/WOFF2/${file}.woff2") format("woff2");
  font-display: swap;
  font-weight: ${weight};
  font-style: ${style};
}

`;

// OTF → TTF
async function otfToTtf() {
  await fs.ensureDir(fontsDir);
  const files = await fs.readdir(fontsDir);

  for (const file of files) {
    if (!file.endsWith('.otf')) continue;

    const input = path.join(fontsDir, file);
    const output = path.join(fontsDir, file.replace('.otf', '.ttf'));

    if (await fs.pathExists(output)) {
      console.log(`⏭ ${file} уже конвертирован`);
      continue;
    }

    try {
      const buffer = await fs.readFile(input);
      const font = Font.create(buffer, { type: 'otf' });
      const ttfBuffer = font.write({ type: 'ttf' });
      await fs.writeFile(output, Buffer.from(ttfBuffer));
      console.log(`✔ ${file} → TTF`);
    } catch (e) {
      console.error(`❌ Ошибка при конвертации ${file}:`, e.message);
    }
  }
}

// TTF → WOFF2 (как у тебя было)
async function ttfToWoff() {
  await fs.ensureDir(woffDir);
  const files = await fs.readdir(fontsDir);

  for (const file of files) {
    if (!file.endsWith('.ttf')) continue;

    const input = path.join(fontsDir, file);
    const output = path.join(woffDir, file.replace('.ttf', '.woff2'));

    if (await fs.pathExists(output)) continue;

    try {
      console.log(`Конвертируем ${file} → WOFF2...`);
      const buffer = await fs.readFile(input);
      const woffBuffer = ttf2woff2(buffer);
      await fs.writeFile(output, woffBuffer);
      await fs.remove(input);
      console.log(`✔ ${file} → WOFF2`);
    } catch (e) {
      console.error(`❌ Ошибка при ${file}:`, e.message);
    }
  }
}

// 🔹 Генерация fonts.css (твоя старая логика)
async function fontStyle() {
  const fontFiles = await fs.readdir(woffDir);
  if (!fontFiles.length) {
    console.log('⚠ Нет WOFF2 файлов для генерации fonts.css');
    return;
  }

  await fs.ensureFile(fontFacesFile);
  await fs.writeFile(fontFacesFile, '');
  const processedFonts = new Set();

  const parseFontFileName = (fileName) => {
    let name = fileName;
    let weight = 400;
    let style = 'normal';
    const lower = fileName.toLowerCase();

    if (italicRegex.test(lower)) style = 'italic';

    if (/-ultrablack/i.test(fileName)) weight = 950;
    else if (/-extrablack/i.test(fileName)) weight = 950;
    else if (/-black/i.test(fileName)) weight = 900;
    else if (/-heavy/i.test(fileName)) weight = 900;
    else if (/-bold/i.test(fileName)) weight = 700;
    else if (/-semibold/i.test(fileName)) weight = 600;
    else if (/-demibold/i.test(fileName)) weight = 600;
    else if (/-medium/i.test(fileName)) weight = 500;
    else if (/-regular/i.test(fileName)) weight = 400;
    else if (/-light/i.test(fileName)) weight = 300;
    else if (/-thin/i.test(fileName)) weight = 100;
    else if (/-hairline/i.test(fileName)) weight = 100;
    else if (/-extralight/i.test(fileName)) weight = 200;
    else if (/-ultralight/i.test(fileName)) weight = 200;

    // имя шрифта до первого дефиса
    if (fileName.includes('-')) name = fileName.split('-')[0];

    name = name.replace(/[_\s]+/g, '');
    return { name, weight, style };
  };

  for (const file of fontFiles) {
    if (!file.endsWith('.woff2')) continue;
    const fileName = file.replace('.woff2', '');
    if (processedFonts.has(fileName)) continue;

    const { name, weight, style } = parseFontFileName(fileName);
    await fs.appendFile(
      fontFacesFile,
      fontFaceTemplate(name, fileName, weight, style),
    );
    processedFonts.add(fileName);
  }

  console.log('✔ fonts.css обновлён!');
}

// 🔹 Основной запуск
async function run() {
  console.log('Обработка шрифтов...\n');
  await otfToTtf();
  await ttfToWoff();
  await fontStyle();
  console.log('\n✅ Всё готово!');
}

run().catch(console.error);
