import { useNavigate } from "react-router-dom";
import { useProducts } from "../../contexts/ProductsContext";
import { IoIosAddCircleOutline } from "react-icons/io";
import { CiSaveDown2 } from "react-icons/ci";
import { FiRepeat } from "react-icons/fi";
import { FaStop } from "react-icons/fa";

export default function Actions() {
  const { handleFreeze, handleNew } = useProducts();
  const nav = useNavigate();
  const handleNav = () => {
    nav("/draft");
  };
  return (
    <div className="w-full flex flex-col justify-center gap-y-1 items-center p-5">
      {/* Upper Buttons */}
      <div className="w-full flex justify-between">
        <button className="w-[65%] bg-blue-600 text-white py-3 rounded flex justify-center items-center gap-2 relative group overflow-hidden">
          <span>حفظ</span>
          <CiSaveDown2 className="inline text-xl opacity-0 group-hover:opacity-100 transition-all duration-300 transform -translate-x-10 group-hover:translate-x-0" />
        </button>
        <button
          onClick={handleNew}
          className="w-[30%] border border-blue-600 text-blue-600 hover:bg-blue-100 py-3 rounded flex justify-center items-center gap-1 relative group overflow-hidden"
        >
          <span>جديد</span>
          <IoIosAddCircleOutline className="inline text-xl opacity-0 group-hover:opacity-100 transition-all duration-300 transform -translate-x-10 group-hover:translate-x-0" />
        </button>
      </div>

      {/* Lower Buttons */}
      <div className="w-full flex justify-between">
        <button
          onClick={handleFreeze}
          className="w-[48%] border border-orange-600 text-orange-600 hover:bg-orange-100 py-3 rounded flex justify-center items-center gap-2 relative group overflow-hidden"
        >
          <span>تجميد</span>
          <FaStop className="inline text-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform -translate-x-10 group-hover:translate-x-0" />
        </button>
        <button
          onClick={handleNav}
          className="w-[48%] border border-blue-600 text-blue-600 hover:bg-blue-100 py-3 rounded flex justify-center items-center gap-2 relative group overflow-hidden"
        >
          <span>استدعاء</span>
          <FiRepeat className="inline opacity-0 group-hover:opacity-100 transition-all duration-300 transform -translate-x-10 group-hover:translate-x-0" />
        </button>
      </div>
    </div>
  );
}
