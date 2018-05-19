let password = document.getElementById("pass");
let confirm_password = document.getElementById("confirm_pass");

function validatePassword() {
    if (password.value !== confirm_password.value) {
        confirm_password.setCustomValidity("Hasła się nie zgadzają");
    } else {
        confirm_password.setCustomValidity('');
    }
}

if (password != null) {
    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
}

function setAdmin(id, block) {
    $.ajax({
        url: "api/set_admin.php",
        method: "post",
        data: {id: id},
        success: function (data) {
            let res = JSON.parse(data);
            if (!res.success) {
                alert(res.error);
                return;
            }

            let bt = $(block);
            bt.toggleClass("btn-success");
            bt.toggleClass("btn-danger");
            if (res.admin === true) {
                bt.html("Tak");
            } else {
                bt.html("Nie");
            }
        }
    });
}

function deleteUser(id, el) {
    $.ajax({
        url: 'api/delete_user.php',
        method: "post",
        data: {id: id},
        success: function (data) {
            let res = JSON.parse(data);
            if (!res.success) {
                alert(res.error);
                return;
            }

            let div = $(el).closest('tr');
            div.remove();
        }
    });
}

function productSave(button) {
    let bt = $(button);
    let td = bt.parent();
    let childrens = td.children();

    let id = childrens[0].value;
    let name = childrens[1].value;

    let success = true;

    if (name === "") {
        return;
    }

    if (id > 0) {
        //update
        $.ajax({
            url: "api/update_product.php",
            method: 'post',
            data: {id: id, name: name},
            success: function (res) {
                let response = JSON.parse(res);
                if (!response.success) {
                    alert(response.error);
                    success = false;
                }
            }
        });
    } else {
        //create new
        $.ajax({
            url: "api/create_product.php",
            method: 'post',
            data: {auction_id: auction_id, name: name},
            success: function (res) {
                let response = JSON.parse(res);

                if (!response.success) {
                    alert(response.error);
                    success = false;
                    return;
                }

                childrens[0].value = response.id;
                bt.html("Zapisz");
                td.append($("<button onclick='productDelete(this);' class='btn btn-danger'>Usuń</button>"));

                $('#tbody').append($('<tr><td>' +
                    '<input type="hidden" name="id" value="0">' +
                    '<input title="Nazwa" name="nazwa" type="text" style="width: 50%">' +
                    '<button onclick="productSave(this);" class="btn btn-success">Dodaj</button>' +
                    '</td></tr>'));
            }
        });
    }
    return success;
}

function productDelete(button) {
    let bt = $(button);
    let td = bt.parent();
    let childrens = td.children();

    let id = childrens[0].value;

    $.ajax({
        url: 'api/delete_product.php',
        method: 'post',
        data: {product_id: id},
        success: function (res) {
            let response = JSON.parse(res);
            console.log(response);
            if (!response.success) {
                alert(response.error);
                return;
            }

            td.parent().remove();

        }
    });
}

function productSaveAll() {

    let table = $('#tbody');
    let tab = table.children();
    for (let i = 0; i < tab.length; i++) {
        let tr = $(tab[i]);
        let button = tr.find("button");
        if (!productSave(button)) {
            break;
        }
    }
}

let offers = $("#offers");
let max_price;

function getOffers(auction_id) {
    $.ajax({
        url: 'api/get_offer_table.php',
        method: 'get',
        data: {id: auction_id},
        success: function (res) {
            offers.html(res);
            $("#price").val(max_price);
        }
    });
}

if (offers.length) {
    getOffers(auction_id);
}

function sendOffer(auction_id, customer_id) {

    let price = $("#price");

    if (price.val() <= max_price) {
        alert("Kwota musi byc większa.");
        return;
    }

    $.ajax({
        url: 'api/create_offer.php',
        method: 'post',
        data: {customer_id: customer_id, auction_id: auction_id, price: price.val()},
        success: function (res) {
            let response = JSON.parse(res);

            if (response.success) {
                max_price = price.val();
                getOffers(auction_id);
            }
        }
    });

}

function deleteAuction(button, id) {
    let bt = $(button);
    let c = $('#count');

    $.ajax({
        url: "api/delete_auction.php",
        method: 'post',
        data: {id: id},
        success: function (res) {
            let response = JSON.parse(res);

            if (response.success) {
                let tr = bt.closest("tr");
                tr.remove();
                console.log("a");
                c.html(parseInt(c.html()) - 1);
            } else {
                alert(response.error);
            }
        }
    });
}


