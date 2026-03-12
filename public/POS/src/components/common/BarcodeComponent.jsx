import BarcodeDesign from "../ui/BarcodeDesign";

const BarcodeComponent = ({ product }) => {
  return (
    <div
      className="barcode-label flex flex-col items-center"
      style={{ width: "143px", height: "95px" }}
    >
      {/* Store name */}
      <div className="text-center font-bold text-[10px] w-full truncate">
        مؤسسة الربيعى للتجارة
      </div>

      {/* Barcode */}
      <div className="flex justify-center items-center w-full my-1">
        <BarcodeDesign value={product?.id} />
      </div>

      {/* Product ID */}
      <div className="text-center text-[10px] font-semibold w-full truncate">
        {product.id}
      </div>

      {/* Price */}
      <div className=" text-[10px] w-full truncate">
        {typeof product?.Units?.[0]?.sallprice === "string"
          ? Number(product?.Units?.[0]?.sallprice).toFixed(2)
          : 0.0}
        {product?.unit_name}
      </div>

      {/* Product Name */}
      <div className="text-center text-[8px] font-bold w-full break-words">
        {product.name}
      </div>
    </div>
  );
};

export default BarcodeComponent;
