import { useSelector } from "react-redux";

export function useCalculateTotals(products = []) {
  // const invoiceSettings = JSON.parse(localStorage.getItem("Invoice Settings"));
          const invoiceSettings = useSelector((state)=> state?.setting?.invoiceSettings);
const TaxMapping = {
        "%": '%',
"ج.م": 'pound',
}
  const subtotal = Number(products.reduce((sum, product) => sum + Number(product?.total), 0));
 let tax = 0;
if (invoiceSettings?.applyTax === 1) {
  if (TaxMapping[invoiceSettings?.taxTypes] === "%") {
    tax = subtotal * (Number(invoiceSettings?.taxValue) / 100);
  } else {
    tax = subtotal > 0 ? Number(invoiceSettings?.taxValue) : 0;
  }
}
  const total = subtotal + tax;
  return { subtotal, tax, total };
}
