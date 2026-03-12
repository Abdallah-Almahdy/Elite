import { HiOutlineDesktopComputer } from "react-icons/hi";
import { IoClose } from "react-icons/io5";
import { useProducts } from "../../contexts/ProductsContext";

export default function OrderActions() {
  const { handleSubmit } = useProducts();
  return (
    <div className="w-full flex gap-x-5 mt-1 text-white">
      <button
        type="submit"
        onClick={handleSubmit}
        className="bg-green-600 bg-opacity-75 hover:bg-opacity-95 w-[80%] flex gap-x-5 justify-center items-center py-[7px] rounded gap-2 relative group overflow-hidden"
      >
        <span>حفظ</span>
        <HiOutlineDesktopComputer className="inline text-xl group transition-all duration-300 transform -translate-x-4 group-hover:translate-x-0" />
      </button>
      <button className="bg-red-600 bg-opacity-80 hover:bg-opacity-95 w-[20%] rounded  flex justify-center items-center gap-2 relative group overflow-hidden">
        <span>الغاء</span>
        <IoClose className="inline text-xl opacity-0 group-hover:opacity-100 transition-all duration-300 transform -translate-x-10 group-hover:translate-x-0" />
      </button>
    </div>
  );
}
