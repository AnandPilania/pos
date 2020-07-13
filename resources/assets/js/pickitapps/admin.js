/*
 *  Document   : app.js
 *  Author     : pixelcave
 *  Description: Main entry point
 *
 */

// Import global dependencies
import './bootstrap.js';

// Import required modules
import Tools from './modules/tools';
import Helpers from './modules/helpers';
import Template from './modules/template';

// Import page js
import AuthLogin from './pages/admin/auth-login';
import PositionsList from './pages/admin/positions-list';
import BusinessTypesList from "./pages/admin/business-types-list";
import EmployeesList from "./pages/admin/employees-list";
import EmployeesAdd from "./pages/admin/employees-add";
import EmployeesEdit from "./pages/admin/employees-edit";
import PositionsAdd from "./pages/admin/positions-add";
import BusinessTypesAdd from "./pages/admin/business-types-add";
import SubscriptionsList from "./pages/admin/subscriptions-list";
import SubscriptionsAdd from "./pages/admin/subscriptions-add";
import ClientsList from "./pages/admin/clients-list";
import ClientsAdd from "./pages/admin/clients-add";
import ClientsEdit from "./pages/admin/clients-edit";
import ProductsList from "./pages/admin/products-list";
import ProductsAdd from "./pages/admin/products-add";
import ProductsEdit from "./pages/admin/products-edit";
import CategoriesList from "./pages/admin/categories-list";
import CategoriesAdd from "./pages/admin/categories-add";

// App extends Template
export default class App extends Template {
    /*
     * Auto called when creating a new instance
     *
     */
    constructor() {
        super();
        this.pages = {
            AuthLogin,
            PositionsList,
            BusinessTypesList,
            EmployeesList,
            EmployeesAdd,
            EmployeesEdit,
            PositionsAdd,
            BusinessTypesAdd,
            SubscriptionsList,
            SubscriptionsAdd,
            ClientsList,
            ClientsAdd,
            ClientsEdit,
            ProductsList,
            ProductsAdd,
            ProductsEdit,
            CategoriesList,
            CategoriesAdd
        }
    }

    /*
     *  Here you can override or extend any function you want from Template class
     *  if you would like to change/extend/remove the default functionality.
     *
     *  This way it will be easier for you to update the module files if a new update
     *  is released since all your changes will be in here overriding the original ones.
     *
     *  Let's have a look at the _uiInit() function, the one that runs the first time
     *  we create an instance of Template class or App class which extends it. This function
     *  inits all vital functionality but you can change it to fit your own needs.
     *
     */

    /*
     * EXAMPLE #1 - Removing default functionality by making it empty
     *
     */

    //  _uiInit() {}


    /*
     * EXAMPLE #2 - Extending default functionality with additional code
     *
     */

    //  _uiInit() {
    //      // Call original function
    //      super._uiInit();
    //
    //      // Your extra JS code afterwards
    //  }

    /*
     * EXAMPLE #3 - Replacing default functionality by writing your own code
     *
     */

    //  _uiInit() {
    //      // Your own JS code without ever calling the original function's code
    //  }
}

// Once everything is loaded
jQuery(() => {
    // Create a new instance of App
   window.Pickitapps = new App();
});
