import ProductIndex from "./components/Products/Index";
import ProductShow from "./components/Products/Show";

import Checkout from "./components/Order/Checkout";
import Summary from "./components/Order/Summary";
export default [{
        path: "/",
        name: "products.index",
        component: ProductIndex
    },
    {
        path: "/product/:slug",
        name: "products.show",
        component: ProductShow
    },
    {
        path: "/checkout",
        name: "order.checkout",
        component: Checkout
    },
    {
        path: "/summary",
        name: "order.summary",
        component: Summary
    }
];