export function calculateTotals(products = []) {
  const invoiceSettings = JSON.parse(localStorage.getItem("Invoice Settings"));
  const subtotal = products.reduce((sum, product) => sum + product?.total, 0);
  const taxValue = invoiceSettings?.taxValue / 100;
  const tax = invoiceSettings?.taxType ==="%" ? subtotal * taxValue : subtotal - invoiceSettings?.taxValue;
  const total = subtotal + tax;
  return { subtotal, tax, total };
}
