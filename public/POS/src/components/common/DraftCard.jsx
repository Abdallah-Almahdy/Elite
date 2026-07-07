import React from "react";

export default function DraftCard({ data, handleReturn }) {
  return (
    <>
      {data.map((draft) => {
        return (
          <div
            key={draft?.id}
            className="bg-white w-full lg:max-w-md px-4 py-2 rounded-lg flex flex-col gap-y-1"
          >
            <h1 className="text-lg font-bold">#{draft?.id}</h1>
            <div className="w-full flex justify-start items-start">
              <p className="text-gray-400" dir="ltr">
                {draft?.date} | {draft?.time}
              </p>
            </div>
            <h2>{draft?.clientName}</h2>
            <div className="flex justify-between items-end h-full">
              <h3 className="font-bold">{draft?.totals?.total} ج.م</h3>
              <button
                onClick={() => handleReturn(draft?.id)}
                className="bg-blue-600 hover:bg-blue-500 rounded-lg text-white px-2.5 py-1.5"
              >
                استعادة
              </button>
            </div>
          </div>
        );
      })}
    </>
  );
}
