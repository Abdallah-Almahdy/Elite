export function calculateTotals(products = []) {
  const subtotal = products.reduce((sum, product) => sum + product?.total, 0);
  const tax = subtotal * 0.14;
  const total = subtotal + tax;
  return { subtotal, tax, total };
}
