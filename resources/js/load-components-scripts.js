// resources/js/components.js
export async function loadComponentScripts() {
  try {
    const manifestUrl =
      window.componentsManifestUrl ||
      new URL('/components-manifest.json', window.location.origin).href;
    const response = await fetch(manifestUrl, { cache: 'no-cache' });
    if (!response.ok) {
      console.warn('⚠️ Манифест компонентов не найден, пропускаем.');
      return;
    }
    const manifest = await response.json();
    const components = manifest.components || [];

    if (components.length === 0) {
      console.log('ℹ️ Нет установленных компонентов с JS-скриптами.');
      return;
    }

    console.log(`Загружаем скрипты для ${components.length} компонентов...`);

    for (const entry of components) {
      const comp = entry.name; // имя компонента (может быть с вложенностью)
      const type = entry.type; // 'file' или 'dir'

      // Плоские компоненты (один файл) – игнорируем, не пытаемся загружать
      if (type === 'file') {
        console.log(`⏭️ Компонент ${comp} (плоский, без скрипта)`);
        continue;
      }

      // Сложные компоненты (папка) – пытаемся загрузить script.js
      try {
        await import(`../views/components/${comp}/script.js`);
        console.log(`✅ Компонент ${comp} загружен`);
      } catch (error) {
        const msg = error.message || '';
        // Если файл отсутствует – тихо пропускаем
        if (
          msg.includes('Failed to fetch') ||
          msg.includes('404') ||
          msg.includes('Cannot find module') ||
          msg.includes('not found')
        ) {
          // Это нормально, если у компонента нет script.js
          continue;
        }
        // Ошибка в существующем файле – выводим предупреждение
         console.log(
          `⚠️ Скриптов у компонента ${comp} нет.` 
        );
      }
    }
  } catch (error) {
    console.warn('⚠️ Ошибка загрузки манифеста компонентов:', error.message);
  }
}
