// Receipt80mm.jsx
import React, { forwardRef, useRef, useImperativeHandle } from "react";
import { printHTML, connectQZ, getPrinters } from "../../services/qzService";
import { useUserSettingsPreference } from "../../contexts/UserSettingsPreferenceContext";

const Receipt80mm = forwardRef(({ invoice, payload }, ref) => {
  const {printerName} = useUserSettingsPreference();
  const domRef = useRef();

  // Expose the print function to parent
  useImperativeHandle(ref, () => ({
    printReceipt: async () => {
      if (!domRef.current) {
        console.error("DOM ref not ready");
        return;
      }
      
      await connectQZ();
       const cloudinaryUrl = "https://res.cloudinary.com/dotkbk7wp/image/upload/v1769617072/oil_gmmvti.png";
const imgBase64 = await fetch(cloudinaryUrl)
  .then(res => res.blob())
  .then(blob => new Promise(resolve => {
    const reader = new FileReader();
    reader.onloadend = () => resolve(reader.result);
    reader.readAsDataURL(blob);
  }));

      const htmlContent = `
    <html>
      <head>
        <style>
          body { direction: rtl; font-family: sans-serif; width: 68mm; font-size: 12px; }
          table { width: 100%; border-collapse: collapse; font-size: 11px; }
          th, td { border: 1px solid #000; padding: 2px; text-align: center; }
        </style>
      </head>
      <body>
        ${domRef.current.innerHTML.replace(/src="[^"]+"/, `src="${imgBase64}"`)}
      </body>
    </html>
  `;

      // const printers = await getPrinters();

      //PASS PRINTER NAME HERE
     if(printerName){
       await printHTML(printerName, htmlContent);
     }
      //    await printHTML("Microsoft Print to PDF", `
      //   <html>
      //     <body>
      //       <h1>TEST PRINT</h1>
      //     </body>
      //   </html>
      // `);
    },
  }));


 
  return (
    <div ref={domRef}>
      {/* LOGO */}
      <div className="row">
        {/* STORE NAME */}
        <div
          style={{
            flex: 1,
            textAlign: "center",
            fontWeight: "bold",
            border: "1px solid black",
            padding: "5px",
            borderRadius: "5px",
            backgroundColor: "#e5e5e5",
          }}
        >
          مؤسسة الربيعى للتجارة
          <div style={{ textAlign: "center" }}>01000132446 - 012547523</div>
        </div>
        <div>
          <img
            src="${imgBase64}" 
            width="50"
            height="50"
            alt="logo"
            style={{
              border: "1px solid black",
            }}
          />
        </div>
      </div>

      <hr style={{ border: 0, borderTop: "2px solid #000" }} />

      {/* HEADER */}
      <div className="row">
        <div style={{ borderLeft: "1px solid #000", width: "50%" }}>
          <div>
            <span style={{ fontWeight: "bold" }}>مسلسل: </span>
            <span>{payload?.id}</span>
          </div>
          <div>
            <span style={{ fontWeight: "bold" }}>المخزن: </span>
            <span>{invoice?.invoice?.stock}</span>
          </div>
          <div>
            <span style={{ fontWeight: "bold" }}>المستخدم:</span>
            <span>{payload?.cashier_name}</span>
          </div>
          <div>
            <span style={{ fontWeight: "bold" }}>م البيع: </span>
            <span></span>
          </div>
        </div>

        <div style={{ paddingRight: "3px" }}>
          <div>
            <span style={{ fontWeight: "bold" }}>التاريخ: </span>
            <span>{invoice?.invoice?.timestamp}</span>
          </div>
          <div>
            <span style={{ fontWeight: "bold" }}>العميل: </span>
            <span>{invoice?.invoice?.userInfo?.clientName}</span>
          </div>
          <div>
            <span style={{ fontWeight: "bold" }}>الكود: </span>
            <span>{invoice?.id}</span>
          </div>
          <div>
            <span style={{ fontWeight: "bold" }}>العنوان: </span>
            <span>{payload?.address}</span>
          </div>
          <div>
            <span style={{ fontWeight: "bold" }}>ت: </span>
            <span>
              {invoice?.invoice?.userInfo?.phone1} /{" "}
              {invoice?.invoice?.userInfo?.optionalPhone}
            </span>
          </div>
        </div>
      </div>

      <hr style={{ border: 0, borderTop: "2px solid #000" }} />

      {/* ITEMS TABLE */}
      <table className="x-border">
        <thead>
          <tr>
            <th style={{ border: "1px solid #000" }}>اسم الصنف</th>
            <th style={{ border: "1px solid #000" }}>الكمية</th>
            <th style={{ border: "1px solid #000" }}>السعر</th>
            <th style={{ border: "1px solid #000" }}>القيمة</th>
          </tr>
        </thead>

        <tbody>
          {invoice?.invoice?.items?.map((item, i) => (
            <tr key={i}>
              <td>{item?.name}</td>
              <td>
                {" "}
                {item?.is_weight
                  ? item?.weight.toFixed(3)
                  : item?.number.toFixed(3)}
              </td>
              <td>{item?.price?.toFixed(2)}</td>
              <td>{item?.total?.toFixed(2)}</td>
            </tr>
          ))}
        </tbody>
      </table>

      <hr style={{ border: 0, borderTop: "2px solid #000" }} />

      {/* TOTALS */}
      <div>
        <div style={{ paddingBottom: "5px" }}>
          <table className="no-border start-align space-x-5 w-full">
            <tbody>
              <tr style={{ height: "50px" }}>
                <td style={{ fontWeight: "bold" }}>عدد الأصناف</td>
                <td>{invoice?.invoice?.items?.length}</td>
                <td rowSpan={3}>
                  <table className="inner-table">
                    <tbody>
                      <tr>
                        <td style={{ fontWeight: "bold" }}>قيمة الأصناف</td>
                        <td>{invoice?.invoice?.subTotal?.toFixed(2)}</td>
                      </tr>
                      <tr>
                        <td style={{ fontWeight: "bold" }}>قيمة الخصم</td>
                        <td>0.00</td>
                      </tr>
                      <tr>
                        <td style={{ fontWeight: "bold" }}>قيمة ض.م</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td>الاجمالى</td>
                        <td>{invoice?.invoice?.subTotal?.toFixed(2)}</td>
                      </tr>
                    </tfoot>
                  </table>
                </td>
              </tr>
              <tr>
                <td style={{ fontWeight: "bold" }}>المدفوع</td>
                <td>0.00</td>
              </tr>
              <tr>
                <td style={{ fontWeight: "bold" }}>المتبقى</td>
                <td>{invoice?.invoice?.subTotal?.toFixed(2)}-</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div
        style={{
          width: "95%",
          alignItems: "center",
          textAlign: "center",
          border: "1px solid #000",
          fontSize: "15px",
          fontWeight: "bolder",
          margin: "auto",
        }}
      >
        <span>الاجمالى: </span>
        <span>{invoice?.invoice?.subTotal?.toFixed(2)}</span>
      </div>

      <hr style={{ border: 0, borderTop: "2px solid #000" }} />

      {/* FOOTER */}
      <div className="center" style={{ fontSize: 12 }}>
        البضاعة المباعة لا ترد وتستبدل خلال 14 يوم فقط
        <br />
      </div>
      <div className="center" style={{ fontSize: 12 }}>
        السويس - الغريب شارع الخضر عمارة 10
      </div>
    </div>
  );
});

export default Receipt80mm;
