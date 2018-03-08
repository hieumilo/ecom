# ecommerce_32
## [Normal User]
- Sign up/Sign in/Sign out
- Authenticate via Facebook, Twitter, Google
- Can see hot trend products 
- Can see recently viewed products
- Can filter Products via alphabet, classify (product), price, category, rating …
- Can see his summary (history order, his cart)
- Can see profile
- Update profile
- Can order product
- Can see information/image/price/number of products
- Can comment in each product (using facebook comment)
- Can see products in the cart when he choice
- Can rating below each products
- Can share social-network below each products
- Can add more product to cart
- Can remove product from cart
- Can suggest more product to admin
- Can chat with admin (vchat, subiz chat, ........)
- Payment with paypal (recommend if can)
## [Admin]
- Can view statistic (chart)
- Can import product from CSV, excel
- Can export statistic report to excel
- Can manage all users
- Can manage all categories (Multi-level Menu)
- Can manage all product (with images)
- Can manage all list order
- Can manage all list request
## [System]
- Send message to chatwork room with order by user
- Send email to custommer with order
- Send statistic of order to admin at end of month

# Step by step
1. Design database
2. Add tasks on redmine + estimate time
3. Init project
4. Init models, add relationship
5. Design static pages
6. Signup / Login / Logout
7. Other pulls

# Step to update task on redmine
1. Change Status to "In Progress", "Due date"
2. Update  "Spent time", "% Done (100)",  before send pull request to trainer 
3. If trainer COMMENT, change "% Done (80)", after that continue to fix comment; if not, move to step 4
4. After MERGED, update task infomation "spent time", "% Done (100)", Status to "Resolved" 

# Notice: 
Trừ pull init project và init model, các pull khác không quá 15 files thay đổi
Các bạn trong team review chéo cho nhau + comment OK vào pull sau khi review xong mà không có lỗi nào

# [Framgia Coding Standard PHP]
https://github.com/framgia/coding-standards/tree/master/vn/php

# PHP-Code-Sniffer check code convention with Sublime Text 
1. https://gist.github.com/tuanpht/98da682333dd1bc8e4516417653158aa 
2. https://github.com/wataridori/framgia-php-codesniffer
