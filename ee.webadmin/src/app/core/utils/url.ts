export function getBaseHref() {
  let baseElements = document.getElementsByTagName('base');
  if (baseElements.length > 0) {
    return baseElements[0].getAttribute('href');
  }
  return '/';
}
