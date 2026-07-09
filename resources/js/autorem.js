let globalScaleFactor = 1;

export function getScaleFactor() {
  return globalScaleFactor;
}

export default function initAutoRem({
  baseSiteWidth = 1440,
  baseFontSize = 16
} = {}) {
  const htmlElement = document.documentElement;
  const widthFactor = 1;

  function updateFontSize() {
    const screenWidth = window.innerWidth;

    const scaleFactor = (screenWidth * widthFactor) / baseSiteWidth;

    if (screenWidth >= baseSiteWidth) {
      globalScaleFactor = scaleFactor;

      const newFontSize = baseFontSize * scaleFactor;
      htmlElement.style.fontSize = `${newFontSize}px`;

      console.log("forced rem mode:", {
        screenWidth,
        baseSiteWidth,
        scaleFactor
      });
    } else {
      globalScaleFactor = 1;
      htmlElement.style.fontSize = "1rem";

      console.log("adaptive rem mode:", {
        screenWidth
      });
    }
  }

  window.addEventListener("resize", updateFontSize);
  updateFontSize();

  // возвращаем scaleFactor сразу
  return {
    scaleFactor: globalScaleFactor,
    destroy: () => {
      window.removeEventListener("resize", updateFontSize);
    }
  };
}