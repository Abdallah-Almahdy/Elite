import React, { useState } from "react";
import { IoClose } from "react-icons/io5";

export default function ShiftModal({ isOpen, onConfirm, onClose }) {
  const [cashAmount, setCashAmount] = useState("");

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 w-full min-h-screen flex justify-center items-center bg-black/50 z-50">
      <div className="w-[420px] bg-white rounded-2xl shadow-xl p-6 relative">
        <button
          onClick={onClose}
          className="absolute left-4 top-4 text-gray-500 hover:text-red-500 transition"
        >
          <IoClose className="text-2xl" />
        </button>

        <h2 className="text-xl font-bold text-center mb-2">إغلاق الوردية</h2>

        <p className="text-sm text-gray-500 text-center mb-6">
          يرجى إدخال المبلغ الموجود فعليًا داخل الخزنة قبل إنهاء الوردية
        </p>

        {/* Input */}
        <div className="flex flex-col gap-2 mb-5">
          <label className="text-sm font-semibold text-gray-700">
            المبلغ الموجود في الخزنة
          </label>

          <input
            type="number"
            value={cashAmount}
            onChange={(e) => setCashAmount(e.target.value)}
            placeholder="أدخل المبلغ"
            className="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div className="flex gap-3">
          <button
            onClick={onClose}
            className="flex-1 border rounded-lg py-2 hover:bg-gray-100"
          >
            إلغاء
          </button>

          <button
            onClick={() => onConfirm(cashAmount)}
            className="flex-1 bg-green-600 text-white rounded-lg py-2 hover:bg-green-700 transition"
          >
            تأكيد إغلاق الوردية
          </button>
        </div>
      </div>
    </div>
  );
}
