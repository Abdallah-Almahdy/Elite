import { useState, useEffect } from "react";
import { IoClose } from "react-icons/io5";
import { useProducts } from "../../contexts/ProductsContext";

export default function CategoryCard() {
  const { data, selectedCategory, setSelectedCategory, setCurrentPage } =
    useProducts();
  const [isOpen, setIsOpen] = useState(false);
  const [visibleCount, setVisibleCount] = useState(5);

  // Dynamically set visible count based on screen width
  useEffect(() => {
    const updateVisibleCount = () => {
      const width = window.innerWidth;
      if (width >= 1280)
        setVisibleCount(5); // large screens
      else if (width >= 1024)
        setVisibleCount(4); // medium screens
      else if (width >= 768)
        setVisibleCount(3); // tablets
      else setVisibleCount(2); // mobile
    };

    updateVisibleCount();
    window.addEventListener("resize", updateVisibleCount);
    return () => window.removeEventListener("resize", updateVisibleCount);
  }, []);

  return (
    <div className="flex flex-col gap-y-2">
      <h1 className="text-xl font-bold">الأقسام</h1>
      <div className="flex flex-wrap gap-2">
        {data.slice(0, visibleCount).map((cat) => {
          return (
            <button
              type="button"
              onClick={() => {
                setSelectedCategory(cat?.id);
                setCurrentPage(1);
              }}
              key={cat?.id}
              className={`whitespace-nowrap px-2 py-1.5 rounded-lg border hover:transition-transform hover:scale-110 hover:duration-300 text-center hover:cursor-pointer ${
                selectedCategory === cat?.id
                  ? `border-blue-600 text-blue-600`
                  : `border-gray-300`
              }`}
            >
              <h2>{cat?.name}</h2>
            </button>
          );
        })}
        {/* "+ المزيد" button */}
        {data.length > visibleCount && (
          <button
            onClick={() => setIsOpen(true)}
            className="cursor-pointer whitespace-nowrap px-4 py-2 rounded-lg border border-gray-300 bg-blue-100 hover:bg-blue-200 flex items-center justify-center"
          >
            <h2>+ المزيد</h2>
          </button>
        )}
      </div>

      {/* Modal */}
      {isOpen && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div className="bg-white rounded-xl p-6 w-[80%] max-h-[80%] overflow-y-auto">
            <div className="flex justify-between items-center mb-4">
              <h2 className="text-xl font-bold">جميع الأقسام</h2>
              <button
                className="text-red-500 text-xl font-bold"
                onClick={() => setIsOpen(false)}
              >
                <IoClose className="text-3xl" />
              </button>
            </div>
            <div className="grid grid-cols-3 gap-4">
              {data
                .flatMap((obj) => Object.keys(obj))
                .map((cat, index) => {
                  const categoryObj = data.find((obj) => obj[cat]);
                  const id = categoryObj[cat]._id;
                  return (
                    <button
                      key={index}
                      className="px-4 py-2 rounded-lg border border-gray-300 text-center"
                      onClick={() => {
                        setSelectedCategory(id);
                        setIsOpen(false);
                      }}
                    >
                      <h2> {cat}</h2>
                    </button>
                  );
                })}
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
