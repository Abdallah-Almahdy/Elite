import Barcode from "react-barcode";

const BarcodeDesign = ({ value }) => {
  return (
    <Barcode
      value={value}
      format="CODE128"
      width={1.2}
      height={15}
      // fontSize={8}
      displayValue={false}
    />
  );
};

export default BarcodeDesign;
