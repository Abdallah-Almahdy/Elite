import React from "react";
import { FaDeleteLeft } from "react-icons/fa6";
import { useProducts } from "../../contexts/ProductsContext";

export default function NumericKeypad({ userPreference }) {
  const { searchValue, handleKeypadInput } = useProducts();
  // Function To Handle The Buttons Entries
  const handleBtnClick = (btn) => {
    if (typeof btn !== "string") {
      handleKeypadInput(searchValue.slice(0, -1));
    } else if (btn === ".") {
      if (!searchValue.includes(".")) handleKeypadInput(searchValue + btn);
    } else {
      handleKeypadInput(searchValue + btn);
    }
  };
  const buttons = [
    "7",
    "8",
    "9",
    "4",
    "5",
    "6",
    "1",
    "2",
    "3",
    <FaDeleteLeft size={25} className="text-red-600 text-opacity-80 z-20" />,
    "0",
    ".",
  ];

  return (
    <div
      className={`border border-gray-300 rounded-2xl shadow-lg w-[18rem] ${userPreference === "smallWrap" ? `lg:p-1` : `p-2`}`}
    >
      <div className="grid grid-cols-3 gap-1 mb-1 text-center">
        {buttons.map((btn) => (
          <button
            key={btn}
            onClick={() => handleBtnClick(btn)}
            className={`bg-white text-lg hover:transition-transform hover:scale-105 hover:duration-300 font-semibold py-2 rounded-xl shadow-sm  active:bg-gray-300 transition flex justify-center items-center ${typeof btn !== "string" ? `border border-red-600 border-opacity-80 hover:bg-red-100` : `border border-gray-300 hover:bg-gray-100`}`}
          >
            {btn}
          </button>
        ))}
      </div>
    </div>
  );
}
