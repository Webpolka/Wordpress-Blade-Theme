// resources/js/styles.js
export async function loadComponentStyles() {
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
      console.log('ℹ️ Нет установленных компонентов с CSS-стилями.');
      return;
    }

    console.log(`Загружаем стили для ${components.length} компонентов...`);

    for (const entry of components) {
      const comp = entry.name;
      const type = entry.type;

      if (type === 'file') {
        console.log(`⏭️ Компонент ${comp} (плоский, без стилей)`);
        continue;
      }

      try {
        // Динамический импорт CSS (если поддерживается)
        await import(`../views/components/${comp}/style.css`);
        console.log(`✅ Стили компонента ${comp} загружены`);
      } catch (error) {
        const msg = error.message || '';
        if (
          msg.includes('Failed to fetch') ||
          msg.includes('404') ||
          msg.includes('Cannot find module') ||
          msg.includes('not found')
        ) {
          continue;
        }
        console.log(
          `⚠️ Стилей у компонента ${comp} нет.`
        );
      }
    }
  } catch (error) {
    console.warn('⚠️ Ошибка загрузки манифеста компонентов:', error.message);
  }
}