import React, { useState } from "react";
import Pagination from "./Pagination";

export default function OrderDetailCard({ selectedProducts }) {
  const [currentPage, setCurrentPage] = useState(1);
  const [productsPerPage, setProductsPerPage] = useState(10);
  const totalPages = Math.ceil(selectedProducts.length / productsPerPage);
  const startIndex = (currentPage - 1) * productsPerPage;
  const visibleProducts = selectedProducts.slice(
    startIndex,
    startIndex + productsPerPage,
  );

  return (
    <div className="w-[95%] flex flex-col justify-between h-full">
      <div>
        <div className="w-full flex justify-between items-center">
          <h1 className="text-base font-semibold pr-2 mb-1">المنتجات</h1>
          <span className="text-sm font-semibold pl-5">
            عدد الأصناف: {selectedProducts.length}
          </span>
        </div>

        {/* Header Row */}
        <div className="w-[95%] mx-auto border px-1 pb-1 mb-2 text-[11px] flex justify-between font-semibold">
          <span className="w-[45%] text-start">الاسم</span>
          <span className="w-[15%] text-center">الوحدة × العدد</span>
          <span className="w-[20%] text-center">السعر</span>
          <span className="w-[20%] text-end">الإجمالي</span>
        </div>

        {/* Products */}
        {visibleProducts.map((product, index) => {
          const unitPrice = Number(product?.unit?.price || 0);
          const number = Number(product?.number || 1);
          const quantity = Number(product?.weight || 0);
          const total = product.is_weight
            ? unitPrice * quantity
            : unitPrice * number;

          return (
            <div
              key={product?.rowKey}
              className="w-[95%] border mx-auto p-1 border-b border-gray-200 text-[11px]"
            >
              <div className="flex justify-between items-center">
                {/* Name */}
                <span className="w-[45%] text-start font-semibold">
                  {product?.name}
                </span>

                {/* Unit × Qty */}
                <span className="w-[15%] text-center text-gray-600">
                  {product?.baseUnit?.unitData?.name ||
                    product?.baseUnit?.label}{" "}
                  ×{" "}
                  {product.is_weight ? quantity.toFixed(3) : number.toFixed(3)}
                </span>

                {/* Price */}
                <span className="w-[20%] text-center text-gray-600">
                  {unitPrice.toFixed(2)} ج.م
                </span>

                {/* Total */}
                <span className="w-[20%] text-end font-bold">
                  {total.toFixed(2)} ج.م
                </span>
              </div>
            </div>
          );
        })}
      </div>
      {selectedProducts.length > productsPerPage && (
        <div className="w-full flex justify-end">
          <Pagination
            currentPage={currentPage}
            totalPages={totalPages}
            onPageChange={(page) => setCurrentPage(page)}
          />
        </div>
      )}
    </div>
  );
}
