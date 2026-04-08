import { useSelector } from "react-redux";

export function useCalculateTotals(products = []) {
  // const invoiceSettings = JSON.parse(localStorage.getItem("Invoice Settings"));
          const invoiceSettings = useSelector((state)=> state?.setting?.invoiceSettings);
const TaxMapping = {
        "%": '%',
"ج.م": 'pound',
}
  const subtotal = products.reduce((sum, product) => sum + product?.total, 0);
  const taxValue = invoiceSettings?.taxValue ? invoiceSettings?.taxValue / 100 : 0;
  const tax = invoiceSettings?.taxTypes ? TaxMapping[invoiceSettings?.taxTypes] ==="%" ? subtotal * taxValue : subtotal > 0 ? Number(invoiceSettings?.taxValue) : 0 : 0;
  const total = subtotal + tax;
  return { subtotal, tax, total };
}
