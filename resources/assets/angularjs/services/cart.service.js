function shoppingCart() {
    this.items = [];
    this.loadItems();
}

shoppingCart.prototype.loadItems = function () {
    var items = sessionStorage != null ? sessionStorage['cart_items'] : null;
    if (items != null && JSON != null) {
        try {
            var items = JSON.parse(items);
            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                if (item.id != null && item.name != null && item.image != null && item.price != null && item.quantity != null) {
                    item = new cartItem(item.id, item.name, item.image, item.price, item.quantity);
                    this.items.push(item);
                }
            }
        }
        catch (err) {
            // ignore errors while loading...
        }
    }
}

shoppingCart.prototype.saveItems = function () {
    if (sessionStorage != null && JSON != null) {
        sessionStorage['cart_items'] = JSON.stringify(this.items);
    }
}

shoppingCart.prototype.addItem = function (id, name, image, price, quantity) {
    quantity = this.toNumber(quantity);
    if (quantity != 0) {
        var found = false;
        for (var i = 0; i < this.items.length && !found; i++) {
            var item = this.items[i];
            if (item.id == id) {
                found = true;
                item.quantity = this.toNumber(item.quantity + quantity);
                if (item.quantity <= 0) {
                    this.items.splice(i, 1);
                }
            }
        }

        if (!found) {
            var item = new cartItem(id, name, image, price, quantity);
            this.items.push(item);
        }

        this.saveItems();
    }
}

shoppingCart.prototype.changeQuantity = function (id, name, image, price, quantity) {
    quantity = this.toNumber(quantity);
    if (quantity != 0) {
        for (var i = 0; i < this.items.length; i++) {
            var item = this.items[i];
            if (item.id == id) {
                item.quantity = this.toNumber(quantity);
                if (item.quantity <= 0) {
                    this.items.splice(i, 1);
                }
            }
        }

        this.saveItems();
    }
}

shoppingCart.prototype.getTotalPrice = function (id) {
    var total = 0;
    for (var i = 0; i < this.items.length; i++) {
        var item = this.items[i];
        if (id == null || item.id == id) {
            total += this.toNumber(item.quantity * item.price);
        }
    }

    return total;
}

shoppingCart.prototype.getTotalCount = function (id) {
    var count = 0;
    for (var i = 0; i < this.items.length; i++) {
        var item = this.items[i];
        if (id == null || item.id == id) {
            count += this.toNumber(item.quantity);
        }
    }

    return count;
}

shoppingCart.prototype.clearItems = function () {
    this.items = [];
    this.saveItems();
}

shoppingCart.prototype.toNumber = function (value) {
    value = value * 1;
    
    return isNaN(value) ? 0 : value;
}

function cartItem(id, name, image, price, quantity) {
    this.id = id;
    this.name = name;
    this.image = image;
    this.price = price * 1;
    this.quantity = quantity * 1;
}
