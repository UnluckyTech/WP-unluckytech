# Invoice Ninja

Tested up to: 6.6.1
Stable tag: 1.0.8
License: GPLv2 or later

WordPress plugin for Invoice Ninja

## Description

The plugin can be used to connect to either the hosted platform API at [invoicing.co](https://invoicing.co) or to a selfhost installation of the app.

* [Terms of Service](https://invoiceninja.com/terms/)
* [Privacy Policy](https://invoiceninja.com/privacy-policy/)

## Features
* Import products from Invoice Ninja as custom pages in WordPress.
* Export WordPress/WooCommerce customers as clients in Invoice Ninja.
* Enable Single sign-on (SSO) for the Client Portal.
* Integrated shopping cart functionality.

<p align="center">
    <img src="https://github.com/invoiceninja/wordpress/blob/main/assets/images/screenshot.webp?raw=true" alt="Screenshot"/>
</p>

## Settings

### Credentials
- **Token**: Enter your Invoice Ninja v5 API token here to authenticate and connect your WordPress site with Invoice Ninja. Using https ensures secure data transfer between the two platforms.
- **URL**: Provide the URL where your Invoice Ninja instance is accessible. If the URL is blank the plugin will connect to the hosted Invoice Ninja platform at invoicing.co.

### Clients
- **Sync Clients**: Enable this option to automatically export WordPress users as clients in Invoice Ninja when they are created or updated. This ensures your client database is always up to date without manual intervention.
- **If Match Is Found**: Specify whether to skip or update a client if a matching client is found in Invoice Ninja during the export process. The default setting is to skip, but you can choose to update existing client information if needed.
- **Included Roles**: Define which WordPress user roles should be included in the export process. Only users with the specified roles will be exported to Invoice Ninja, allowing for precise control over your client synchronization.

### Products
- **Sync Products**: Enable this feature to automatically import products from Invoice Ninja into WordPress on an hourly basis. These products will be created as custom pages in WordPress, providing an up-to-date product catalog on your site.
- **Online Purchases**: Configure how your customers can purchase products. Set to 'Single' for a 'Buy Now' button for immediate purchase or 'Multiple' for an 'Add to Cart' button, allowing customers to add items to their cart for later checkout.

### Localization
- **Product Label**: Singular label to use for individual products.
- **Products Label**: Customize the plural label used for multiple products. This label will appear wherever multiple products are listed.

> [!NOTE]  
> Note: Additional fields will be displayed if Online Purchases and/or Inventory Tracking are enabled, providing more detailed configuration options.

### Templates
- **Product Template**: Define the HTML template used to generate the product list page. 
- **Image Template**: If your products in Invoice Ninja have an Image URL set, the images will be imported and displayed alongside the product information in WordPress.

### Custom CSS
- **Product Page**: Add custom CSS to style individual product pages. This gives you the flexibility to match the product pages with your site’s overall design.
- **Products Page**: Add custom CSS to style the product listing page, ensuring a cohesive look across all product-related content on your site.

## Shortcodes

- **[purchase product_id=""]**: This shortcode will display either an 'Add to Cart' button or a 'Buy Now' button, depending on the Online Purchases setting.
- **[client_portal label="" sso=""]**: This shortcode generates a button that allows your customers to access their client portal. If the SSO (Single Sign-On) parameter is set to true, they won't need to enter their password.

> [!WARNING]  
> WARNING: Only use the [client_portal] shortcode if your customers are confirming their email addresses. Without this confirmation, a malicious user could create a fake account and gain unauthorized access to data in Invoice Ninja.

## Credits
* [Hillel Coren](https://hillel.dev)
* [Oliver Flueckiger](https://www.oliver-flueckiger.ch)
