import React from "react";

export default function UserInfoSummary({
  selectedOrder,
  selectedPayment,
  formData,
  phone,
  address,
}) {
  return (
    <div className="w-full flex flex-col gap-y-5">
      <h1 className="text-lg font-semibold pr-5 pt-3">مراجعة الطلب</h1>
      <div className="w-[90%] mx-auto bg-blue-100 bg-opacity-30 p-2 rounded-lg">
        <h1 className="text-lg font-semibold">معلومات العميل</h1>
        <table className="w-full border-separate border-spacing-1">
          <tbody>
            <tr>
              <td className="font-medium w-20">الاسم:</td>
              <td>{formData.clientName}</td>
            </tr>
            <tr>
              <td className="font-medium">الهاتف:</td>
              <td>{phone}</td>
            </tr>
            <tr>
              <td className="font-medium">العنوان:</td>
              <td>{address}</td>
            </tr>
            <tr>
              <td className="font-medium">ملاحظات:</td>
              <td>{formData.notes}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div className="w-[90%] mx-auto bg-blue-100 bg-opacity-30 p-2 rounded-lg">
        <h1 className="text-lg font-semibold">تفاصيل الطلب</h1>
        <table className="w-full border-separate border-spacing-1">
          <tbody>
            <tr>
              <td className="font-medium w-20">نوع الطلب:</td>
              <td>{selectedOrder}</td>
            </tr>
            <tr>
              <td className="font-medium w-28">طريقة الدفع:</td>
              <td>{selectedPayment}</td>
            </tr>
            <tr>
              <td className="font-medium w-20">الوقت المتوقع:</td>
              <td>30 - 45 دقيقه</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  );
}
