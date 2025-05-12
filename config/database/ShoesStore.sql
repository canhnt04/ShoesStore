CREATE TABLE `Role` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) UNIQUE NOT NULL,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `Permission` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100) UNIQUE NOT NULL,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `RolePermission` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `User` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(255) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `role_id` int NOT NULL,
  `status` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `Employee` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int UNIQUE NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone` varchar(15) UNIQUE NOT NULL,
  `address` text,
  `salary` decimal(10,2),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `Customer` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int UNIQUE NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone` varchar(15) UNIQUE NOT NULL,
  `gmail` varchar(255) UNIQUE NOT NULL,
  `address` text,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `Product` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `supplier_id` int NOT NULL,
  `category_id` int NOT NULL,
  `status` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `ProductDetail` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `description` text,
  `quantity` int NOT NULL,
  `size` varchar(10) NOT NULL,
  `color` varchar(50) NOT NULL,
  `material` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `Category` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `Cart` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `CartDetail` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `cart_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `Orders` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `note` text,
  `status_id` int,
  `created_at` datetime,
  `updated_at` datetime
);
CREATE TABLE `OrderDetail` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime,
  `updated_at` datetime
);
CREATE TABLE `Orders_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime,
  `updated_at` datetime
) ;
CREATE TABLE `Orders_status_detail` (
  `order_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `status_id` int(11) NOT NULL,
  `created_at` datetime,
  `updated_at` datetime
  
);



CREATE TABLE `Supplier` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) UNIQUE NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `ImportReceipt` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `import_date` datetime NOT NULL,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `ImportReceiptDetail` (
  `id` int PRIMARY KEY NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime,
  `updated_at` datetime
);

ALTER TABLE `RolePermission` ADD FOREIGN KEY (`role_id`) REFERENCES `Role` (`id`);

ALTER TABLE `RolePermission` ADD FOREIGN KEY (`permission_id`) REFERENCES `Permission` (`id`);

ALTER TABLE `User` ADD FOREIGN KEY (`role_id`) REFERENCES `Role` (`id`);

CREATE TABLE `User_Employee` (
  `User_id` int,
  `Employee_user_id` int,
  PRIMARY KEY (`User_id`, `Employee_user_id`)
);

ALTER TABLE `User_Employee` ADD FOREIGN KEY (`User_id`) REFERENCES `User` (`id`);

ALTER TABLE `User_Employee` ADD FOREIGN KEY (`Employee_user_id`) REFERENCES `Employee` (`user_id`);


CREATE TABLE `User_Customer` (
  `User_id` int,
  `Customer_user_id` int,
  PRIMARY KEY (`User_id`, `Customer_user_id`)
);

ALTER TABLE `User_Customer` ADD FOREIGN KEY (`User_id`) REFERENCES `User` (`id`);

ALTER TABLE `User_Customer` ADD FOREIGN KEY (`Customer_user_id`) REFERENCES `Customer` (`user_id`);


ALTER TABLE `Product` ADD FOREIGN KEY (`supplier_id`) REFERENCES `Supplier` (`id`);

ALTER TABLE `Product` ADD FOREIGN KEY (`category_id`) REFERENCES `Category` (`id`);

ALTER TABLE `ProductDetail` ADD FOREIGN KEY (`product_id`) REFERENCES `Product` (`id`);

ALTER TABLE `Cart` ADD FOREIGN KEY (`user_id`) REFERENCES `User` (`id`);

ALTER TABLE `CartDetail` ADD FOREIGN KEY (`cart_id`) REFERENCES `Cart` (`id`);

ALTER TABLE `CartDetail` ADD FOREIGN KEY (`product_id`) REFERENCES `Product` (`id`);

ALTER TABLE `Orders` ADD FOREIGN KEY (`user_id`) REFERENCES `User` (`id`);

ALTER TABLE `OrderDetail` ADD FOREIGN KEY (`order_id`) REFERENCES `Orders` (`id`);

ALTER TABLE `OrderDetail` ADD FOREIGN KEY (`product_id`) REFERENCES `Product` (`id`);

ALTER TABLE `Orders_status` ADD PRIMARY KEY (`id`);

ALTER TABLE `Orders_status_detail` ADD FOREIGN KEY (`order_id`) REFERENCES `Orders` (`id`);

ALTER TABLE `Orders_status_detail` ADD FOREIGN KEY (`status_id`) REFERENCES `Orders_status` (`id`);



ALTER TABLE `ImportReceipt` ADD FOREIGN KEY (`user_id`) REFERENCES `User` (`id`);

ALTER TABLE `ImportReceipt` ADD FOREIGN KEY (`supplier_id`) REFERENCES `Supplier` (`id`);

ALTER TABLE `ImportReceiptDetail` ADD FOREIGN KEY (`id`) REFERENCES `ImportReceipt` (`id`);

ALTER TABLE `ImportReceiptDetail` ADD FOREIGN KEY (`product_id`) REFERENCES `Product` (`id`);
