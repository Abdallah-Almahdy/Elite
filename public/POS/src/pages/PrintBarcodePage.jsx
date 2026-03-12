import { useEffect } from "react";
import BarcodeComponent from "../components/common/BarcodeComponent";
import { useProducts } from "../contexts/ProductsContext";

export default function PrintBarcodePage() {
  const { filteredProductsByName } = useProducts();

  const handlePrint = () => {
    window.print();
  };

  useEffect(() => {
    const handleKeyEnter = (e) => {
      if (e.key === "Enter") {
        handlePrint();
      }
    };
    window.addEventListener("keydown", handleKeyEnter);
    return () => {
      window.removeEventListener("keydown", handleKeyEnter);
    };
  }, []);

  return (
    <div className="w-full">
      {/* Inline print styles for thermal label printing */}
      <style>
        {`
        @media print {
          body * {
            visibility: hidden;
          }

          .print-all-products,
          .print-all-products * {
            visibility: visible;
          }

          .print-all-products {
            display: flex !important;
            flex-direction: column;
            flex-wrap: nowrap;
            gap: 0;
            margin: 0;
            padding: 0;
          }

          .product-print-item {
            width: 38mm;  /* label width */
            height: 25mm; /* label height */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            break-inside: avoid;
            page-break-inside: avoid;
            page-break-after: always; /* each product on a new label */
            margin: 0;
            padding: 0;
          }

          @page {
            size: 40mm 27mm; /* exact label size */
            margin: 0;
          }
        }
      `}
      </style>

      {/* Hidden container for printing all products */}
      <div className="print-all-products">
        {filteredProductsByName.map((product) => (
          <div key={product.id} className="product-print-item">
            <BarcodeComponent product={product} />
          </div>
        ))}
      </div>

      {/* Print button */}
      <div className="w-full flex justify-center items-center my-4">
        <button
          onClick={handlePrint}
          className="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          طباعة
        </button>
      </div>
    </div>
  );
}
