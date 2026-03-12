import React from "react";
import PaymentCard from "../ui/PaymentCard";
export default function PaymentMethods({
  selectedPayment,
  setSelectedPayment,
  formData,
  error,
  remainingValue,
  setRemainingValue,
}) {
  return (
    <div className="w-full">
      <h1 className="text-base font-semibold pr-5">طرق الدفع</h1>
      <div className="w-[95%] mx-auto flex flex-col gap-y-2">
        <PaymentCard
          selectedPayment={selectedPayment}
          setSelectedPayment={setSelectedPayment}
          formData={formData}
          error={error}
          remainingValue={remainingValue}
          setRemainingValue={setRemainingValue}
        />
      </div>
    </div>
  );
}
