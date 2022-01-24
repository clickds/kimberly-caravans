export const formatJsonDate = (dateString) => {
  if (dateString == null) {
    return 'N/A';
  }
  let date = new Date(dateString);

  return date.toLocaleDateString('default', {year: 'numeric', month: 'long', day: 'numeric'});
};

export const formatPrice = (currencySymbol, price) => {
  return `${currencySymbol}${price.toLocaleString()}`;
};

export const convertArrayItemsToIntegers = (array) => {
  return array.map(arrayItem => parseInt(arrayItem));
};