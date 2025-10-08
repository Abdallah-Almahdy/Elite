import React, { useState } from 'react';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { PosCart } from '@/components/ui/PosCart';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog"

const POSInterface = ({ categories, products }) => {
    const [selectedCategory, setSelectedCategory] = useState(null);
    const [cart, setCart] = useState([]);
    const [showDialog, setShowDialog] = useState(false);
    const [selectedProduct, setSelectedProduct] = useState(null);
    const [productOptions, setProductOptions] = useState([]);
    const [productAddOns, setProductAddOns] = useState([]);
    const [selectedOptions, setSelectedOptions] = useState({});
    const [selectedAddOns, setSelectedAddOns] = useState([]);
    const [quantity, setQuantity] = useState(1);

    const handleProductClick = (product) => {

        console.log(product.options);
        console.log(product.adds_on);

        setSelectedProduct(product);
        setShowDialog(true);
        setQuantity(1);
        setSelectedAddOns([]);
        setSelectedOptions({});

        // fetch options & addons (either from props or lazily from backend)
        setProductOptions(product.options || []);
        setProductAddOns(product.adds_on || []);
    };

    const filterProducts = (product) => {
        if (product.category_id === selectedCategory) {
            product.price.toFixed(1)
            return product;
        }
    };

    const filteredProducts = selectedCategory
        ? products.filter(filterProducts)
        : products;



    // Check if all required options are selected
    const areAllOptionsSelected = () => {
        if (!selectedProduct || !productOptions.length) return true;

        // Check if all options have a selected value
        for (const option of productOptions) {
            if (!selectedOptions[option.id]) {
                return false;
            }
        }
        return true;
    };

    // Get validation message for missing options
    const getValidationMessage = () => {
        if (!selectedProduct || !productOptions.length) return '';

        const missingOptions = productOptions.filter(option => !selectedOptions[option.id]);
        if (missingOptions.length > 0) {
            return `يجب اختيار: ${missingOptions.map(opt => opt.name).join(', ')}`;
        }
        return '';
    };

    const addToCart = () => {
        if (!selectedProduct || !areAllOptionsSelected()) return;

        // Get selected option details
        const optionDetails = {};
        Object.entries(selectedOptions).forEach(([optionId, valueId]) => {
            const option = selectedProduct.options.find(opt => opt.id == optionId);
            const value = option?.values.find(val => val.id == valueId);
            if (option && value) {
                optionDetails[optionId] = {
                    name: option.name,
                    value: value.name,
                    price: value.price
                };
            }
        });

        // Get selected add-on details
        const addOnDetails = selectedAddOns.map(addOnId => {
            const addOn = selectedProduct.adds_on.find(addon => addon.id == addOnId);
            return addOn ? {
                id: addOn.id,
                name: addOn.name,
                price: addOn.price
            } : null;
        }).filter(Boolean);

        // Calculate total price for this item
        const optionPrice = Object.values(optionDetails).reduce((sum, opt) => sum + opt.price, 0);
        const addOnPrice = addOnDetails.reduce((sum, addon) => sum + addon.price, 0);
        const totalItemPrice = selectedProduct.price + optionPrice + addOnPrice;

        const cartItem = {
            ...selectedProduct,
            quantity,
            selectedOptions: optionDetails,
            selectedAddOns: addOnDetails,
            totalItemPrice,
            uid: `${selectedProduct.id}-${Date.now()}-${Math.random()}`
        };

        setCart(prev => [...prev, cartItem]);
        // setShowDialog(false);
    };
    return (
        <div className="flex h-screehttp://localhost:8000/uploads/products/1489216287.jpgn bg-gray-50">

            {/* Left Panel - Categories and Products */}
            <div className="flex-1 p-6 overflow-y-auto">
                {/* Categories Section */}
                <div className="mb-6">
                    <h2 className="text-2xl font-bold mb-4 text-gray-800">الأقسام</h2>
                    <div className="overflow-x-auto scrollbar-hide">
                        <div className="flex space-x-4 pb-2 min-w-max">
                            {
                                (!categories.length) ?
                                    (<p>لا يوجد اقسام</p>)
                                    :
                                    (categories.map((category) => (
                                        <div
                                            key={category.id}
                                            className={`flex flex-col items-center cursor-pointer transition-all hover:scale-105 flex-shrink-0 ${selectedCategory === category.id ? 'opacity-100' : 'opacity-80'
                                                }`}
                                            onClick={() => setSelectedCategory(selectedCategory === category.id ? null : category.id)}
                                        >
                                            <div className={`w-16 h-16 rounded-full overflow-hidden border-3 transition-all ${selectedCategory === category.id
                                                ? 'border-blue-500 shadow-lg ring-2 ring-blue-200'
                                                : 'border-gray-300 hover:border-gray-400'
                                                }`}>
                                                <img


                                                    src={`http://localhost:8000/uploads/${category.photo}`}
                                                    alt={category.name}
                                                    className="w-full h-full object-cover"
                                                />
                                            </div>
                                            <span className="mt-1 text-xs font-medium text-gray-700 text-center max-w-20">
                                                {category.name}
                                            </span>
                                        </div>
                                    )))

                            }


                        </div>
                    </div>
                </div>

                <Dialog open={showDialog} onOpenChange={setShowDialog}>
                    <DialogContent className="max-w-md">
                        {selectedProduct && (
                            <>
                                <DialogHeader>
                                    <DialogTitle>Customize {selectedProduct.name}</DialogTitle>
                                </DialogHeader>

                                {/* Quantity */}
                                <div className="my-2">
                                    <label className="text-sm font-medium">الكمية:</label>
                                    <input
                                        type="number"
                                        min={1}
                                        value={quantity}
                                        onChange={e => setQuantity(parseInt(e.target.value))}
                                        className="border rounded p-1 w-16 ml-2"
                                    />
                                </div>

                                {/* Options */}
                                {productOptions.length > 0 && (
                                    <div className="my-2">
                                        <label className="text-sm font-medium">الخيارات:</label>
                                        {productOptions.map(option => (
                                            <div key={option.id} className="my-1">
                                                <p className="text-xs font-semibold">{option.name}</p>
                                                {option.values.map(value => (
                                                    <label key={value.id} className="flex items-center space-x-2 text-sm">
                                                        <input
                                                            type="radio"
                                                            name={`option-${option.id}`}
                                                            value={value.id}
                                                            checked={selectedOptions[option.id] === value.id}
                                                            onChange={() =>
                                                                setSelectedOptions({ ...selectedOptions, [option.id]: value.id })
                                                            }
                                                        />
                                                        <span>{value.name} (+{value.price})</span>
                                                    </label>
                                                ))}
                                            </div>
                                        ))}
                                    </div>
                                )}

                                {console.log(productAddOns.length > 0, 'hey')}
                                {/* Add-Ons */}
                                {productAddOns.length > 0 && (

                                    <div className="my-2">
                                        <label className="text-sm font-medium">الإضافات:</label>
                                        {productAddOns.map(addon => (
                                            <label key={addon.id} className="flex items-center space-x-2 text-sm">
                                                <input
                                                    type="checkbox"
                                                    checked={selectedAddOns.includes(addon.id)}
                                                    onChange={() => {
                                                        if (selectedAddOns.includes(addon.id)) {
                                                            setSelectedAddOns(selectedAddOns.filter(id => id !== addon.id));
                                                        } else {
                                                            setSelectedAddOns([...selectedAddOns, addon.id]);
                                                        }
                                                    }}
                                                />
                                                <span>{addon.name} (+{addon.price})</span>
                                            </label>
                                        ))}
                                    </div>
                                )}
                                {/* Validation Message */}
                                {!areAllOptionsSelected() && (
                                    <div className="bg-red-50 border border-red-200 rounded-lg p-3">
                                        <p className="text-sm text-red-600 text-center">
                                            ⚠️ {getValidationMessage()}
                                        </p>
                                    </div>
                                )}
                                <div className="mt-4 flex justify-between">
                                    <button
                                        onClick={addToCart}
                                        disabled={!areAllOptionsSelected()}
                                        className={`flex-1 py-3 px-4 rounded-lg font-medium transition-all ${areAllOptionsSelected()
                                            ? 'bg-blue-600 hover:bg-blue-700 text-white'
                                            : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                            }`}
                                    >
                                        أضف للسلة
                                    </button>

                                    <button
                                        onClick={() => setShowDialog(false)}
                                        className="text-sm text-gray-500 underline"
                                    >
                                        إغلاق
                                    </button>
                                </div>
                            </>
                        )}
                    </DialogContent>
                </Dialog>


                {/* Products Section */}
                <div>
                    <div className="flex items-center justify-between mb-6">
                        <h2 className="text-2xl  font-bold text-gray-800">المنتجات</h2>
                        {selectedCategory && (
                            <Badge variant="outline" className="text-sm">
                                {categories.find(c => c.id === selectedCategory)?.name}
                            </Badge>
                        )}
                    </div>
                    {/* <div className="grid grid-cols-10 gap-2">
                        {

                            (!filteredProducts.length) ?
                                (<p>لا يوجد منتجات</p>)
                                :
                                filteredProducts.map((product) => (
                                    <Card
                                        key={product.id}
                                        className="cursor-pointer text-center hover:shadow-xl transition-all duration-300 border-0 hover:border-blue-300 hover:scale-[1.02] bg-white rounded-xl overflow-hidden"
                                        onClick={() => addToCart(product)}
                                    >
                                        <CardContent className="p-0">
                                            <div className="aspect-[4/3] overflow-hidden">
                                                <img
                                                    src={`http://localhost:8000/uploads/${product.photo}`}
                                                    alt={product.name}
                                                    className="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                                                />
                                            </div>
                                            <div className="p-3">
                                                <h3 className="font-semibold text-gray-800 text-x-sm line-clamp-2 mb-1">{product.name}</h3>
                                                {product.qnt == 0 || product.active == 0 ?
                                                    <Badge variant="destructive">غير متوفر</Badge>
                                                    :
                                                    <p className="text-base font-bold text-green-600"> {product.price}</p>
                                                }
                                            </div>
                                        </CardContent>
                                    </Card>
                                ))}
                    </div> */}
                    <div className="grid grid-cols-10 gap-2">
                        {
                            (!filteredProducts.length) ?
                                (<p>لا يوجد منتجات</p>)
                                :
                                filteredProducts.slice(0, 50).map((product) => (
                                    <Card
                                        key={product.id}
                                        className="cursor-pointer text-center hover:shadow-xl transition-all duration-300 border hover:border-blue-300 hover:scale-[1.01] bg-white rounded-lg overflow-hidden"
                                        onClick={() => handleProductClick(product)}
                                    >
                                        <CardContent className="p-1">
                                            <div className="aspect-square overflow-hidden">

                                                <img
                                                    src={`http://localhost:8000/uploads/${product.photo}`}
                                                    alt={product.name}
                                                    // className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                                    className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                                />
                                            </div>
                                            <div className="p-1">
                                                <h3 className="font-semibold text-gray-800 text-xs leading-tight line-clamp-2">{product.name}</h3>
                                                {product.qnt == 0 || product.active == 0 ?
                                                    <Badge variant="destructive" className="text-[10px] py-0.5 px-1">غير متوفر</Badge>
                                                    :
                                                    <p className="text-xs font-bold text-green-600">{product.price}</p>
                                                }
                                            </div>
                                        </CardContent>
                                    </Card>
                                ))
                        }
                    </div>
                </div>
            </div>

            {/* Right Panel - Cart */}
            <PosCart cart={cart} setCart={setCart} />
        </div>
    );
};

export default POSInterface;
