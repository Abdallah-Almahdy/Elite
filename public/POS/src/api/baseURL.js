import axios from "axios";

const api = axios.create({
    baseURL: "https://sicilia-test.srv599157.hstgr.cloud/api",
});

export const sendOrderToBackend = async (order) => {
 const response = await api.post("/invoices", order)

 const returnedInvoice = response.data;
 return returnedInvoice.data;
};

export const sendDraftToBackend = async (draft) => {
  return new Promise((resolve, reject) => {
    setTimeout(() => {
      if (navigator.onLine) {
        resolve('Draft sent successfully');
      } else {
        reject('Offline: cannot send draft');
      }
    }, 1000); // simulate 1s network delay
  });
};

export const sendUserToBackend = async (user) => {
  try {
    const formDataObject = Object.fromEntries(user.entries());
    const response = await api.post("/specialRegister", user);
    
    const returnedUser =  response.data;
    const newUser = {
      id: returnedUser?.user?.id,
      name: returnedUser?.user?.name,
      email: formDataObject?.phonenum,
      customer_info: {
        address1: `${formDataObject?.Country} ${formDataObject?.city} ${formDataObject.street} ${formDataObject?.building} ${formDataObject?.floor} ${formDataObject?.apartment}`,
        phone: formDataObject?.phonenum
      },
      
    };
    sessionStorage.setItem('selectedUser', JSON.stringify(newUser));
  }
  catch(err){
    if (!err.response) {
      throw new Error("Offline: cannot send user");
    }
    throw new Error(
      err.response.data?.message || "Failed to send user"
    );
  }
};


export default api;