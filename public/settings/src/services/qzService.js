import notify from "../hooks/Notification";
import qz from "qz-tray";

export const connectQZ = async () => {
  if (qz.websocket.isActive()) return;

  qz.security.setCertificatePromise(function (resolve, reject) {
    resolve("");
  });

  qz.security.setSignaturePromise(function (toSign) {
    return function (resolve, reject) {
      resolve("");
    };
  });

  await qz.websocket.connect({
    host: "192.168.72.1",
    ports: { secure: 8181, insecure: 8182 },
  });
};

export const getPrinters = async () => {
  return await qz.printers.find();
};

export const printHTML = async (printerName, htmlContent) => {
  try {
    if (!qz.websocket.isActive()) {
      notify("QZ Tray غير متصل", "error");
      return;
    }

    const availablePrinters = await qz.printers.find();

    localStorage.setItem("Printer Name", JSON.stringify(availablePrinters));

    const printerExists = availablePrinters.includes(printerName);

    if (!printerExists) {
      notify("اسم الطابعة غير معرف على الجهاز", "error");
      return;
    }

    const config = qz.configs.create(printerName);

    const data = ["\x1B\x40", "TEST PRINT\n", "\n\n\n", "\x1D\x56\x00"];

    await qz.print(config, data);

    notify("تمت الطباعة بنجاح", "success");
  } catch (err) {
    if (err.message?.includes("No printers")) {
      notify("لا توجد طابعات متصلة", "error");
    } else if (err.message?.includes("WebSocket")) {
      notify("فشل الاتصال مع QZ Tray", "error");
    } else if (err.message?.includes("Access denied")) {
      notify("لا يوجد صلاحية للطباعة", "error");
    } else {
      notify("حدث خطأ أثناء الطباعة", "error");
    }
    // throw err;
  }
};

// export const printHTML = async (printerName, htmlContent) => {
//   try{
//     const availablePrinters = getPrinters();
//     localStorage.setItem("Printer Name", JSON.stringify(availablePrinters))
//     if(availablePrinters.includes(printerName)){
//       const config = qz.configs.create(printerName);
//         const data = [
//   '\x1B\x40',
//   'TEST PRINT\n',
//   '\n\n\n',
//   '\x1D\x56\x00'
// ];
//   await qz.print(config, data);

//     }
//     else{
//       notify("اسم الطابعة غير معرف", "warn")
//     }
//   }
//   catch(err){
// console.log(err)
//   }

// QZ supports HTML printing
// const data = [
//   {
//     type: "html",
//     format: "html",
//     data: htmlContent
//   }
// ];

// const config = qz.configs.create("Microsoft Print to PDF");

// const data = [
//   {
//     type: "raw",
//     format: "plain",
//     data: "TEST PRINT",
//   },
// ];

// console.log("Sending to printer:", printerName);
// console.log("HTML length:", htmlContent.length);
//   await qz.print(config, data);
