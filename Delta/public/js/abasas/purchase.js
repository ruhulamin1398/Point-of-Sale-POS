$(document).ready(function () {
    var databaseProducts;

    $(function () {


        var link = $("#homeRoute").val().trim() + "/api/all-products";
        console.log(link);

        $.get(link, function (data) {
            databaseProducts = data;

        });

    })


    var purchaseTableData = {};

    //done
    function calIndivitualDiscount() {
        var discountType = $("#purchaseProductInputDiscountType").val().trim();
        var price = $("#purchaseProductInputPrice").val().trim();
        var quantity = $("#purchaseProductInputQuantity").val().trim();
        var discountValue = $("#purchaseProductInputdiscount").val().trim();
        if (discountType == 1) {
            discountValue = (price * quantity * discountValue * 0.01);
            $("#purchaseProductInputDiscountValue").val(discountValue);
            return discountValue;
        } else {
            $("#purchaseProductInputDiscountValue").val(discountValue);
            return discountValue;
        }
    }
    //done
    function calIndivitualTotal() {

        var price = $("#purchaseProductInputPrice").val().trim();
        var discount = calIndivitualDiscount();
        var quantity = $("#purchaseProductInputQuantity").val().trim();
        var total = (price * quantity) - discount;
        $("#purchaseProductInputTotal").val(total);
        if (total < 0)
            return 0;
        else
            return 1;


    }

    //done
    function setIndivitualInputFieldDefault() {

        $("#purchaseProductInputSubmit").attr("disabled", true);

        $("#purchaseProductInputId").val('');
        $("#productIdHidden").val(0);
        $("#purchaseProductInputName").val('');
        $("#purchaseProductInputPrice").val('');
        $("#purchaseProductInputdiscount").val(0);
        $("#purchaseProductInputQuantity").val('');
        $("#purchaseProductInputTotal").val(0);
    }

    //done
    function setIndivitualInputDetailsDefault() {

        $("#purchaseProductInputSubmit").attr("disabled", true);

        $("#productIdHidden").val(0);
        $("#purchaseProductInputName").val('');
        $("#purchaseProductInputPrice").val('');
        $("#purchaseProductInputdiscount").val(0);
        $("#purchaseProductInputQuantity").val('');
        $("#purchaseProductInputTotal").val(0);
        $("#purchaseProductError").show();
    }

    $("#purchaseProductError").hide();

    //done
    function purchaseProductInputOnInput() {

        $("#purchaseProductInputSubmit").attr("disabled", true);

        var product_id = parseInt($("#purchaseProductInputId").val().trim());


        var product = databaseProducts[product_id];
        if (typeof product == 'undefined') {

            setIndivitualInputDetailsDefault();

            $("#purchaseProductInputSubmit").attr("disabled", true);
        } else {
            $("#purchaseProductError").hide();

            $("#productIdHidden").val(product.id);
            $("#purchaseProductInputName").val(product.name);
        }

    }
    
    //done
    $("#purchaseProductInputId").on('input', function () {

        purchaseProductInputOnInput()


    });


    
    //                               ****************************************
    //                               ########## On Price Input #############
    //  


    //done
    $("#purchaseProductInputPrice").on('input', function () {

        calIndivitualTotal();
        if(  !($("#purchaseProductInputName").val() == '')  &&  !($("#purchaseProductInputPrice").val() == '')  &&  !($("#purchaseProductInputQuantity").val() == '')  ){
            
            $("#purchaseProductInputSubmit").attr("disabled", false);
        }
        else{
            $("#purchaseProductInputSubmit").attr("disabled", true);

        }

    });


    //done
    $("#purchaseProductInputPrice").keypress(function (e) {
        if (e.originalEvent.key === 'Enter' || e.originalEvent.keyCode === 13) {
            if( !($("#purchaseProductInputPrice").val() == '')  ){
                $('#purchaseProductInputQuantity').focus();
            }
        }

    });
    
    //                               ****************************************
    //                               ########## On Discount Input #############
    //  

    //done
    $("#purchaseProductInputdiscount").on('input', function () {

        calIndivitualTotal();
    });





    //                               ****************************************
    //                               ########## On Quantity input #############
    //  

    //done
    $("#purchaseProductInputQuantity").on('input', function () {

        calIndivitualTotal();
        
        if(  !($("#purchaseProductInputName").val() == '')  &&  !($("#purchaseProductInputPrice").val() == '')  &&  !($("#purchaseProductInputQuantity").val() == '')  ){
            
            $("#purchaseProductInputSubmit").attr("disabled", false);
        }
        else{
            $("#purchaseProductInputSubmit").attr("disabled", true);

        }

    });
    //done
    $("#purchaseProductInputQuantity").keypress(function (e) {
        if (e.originalEvent.key === 'Enter' || e.originalEvent.keyCode === 13) {
            if(  !($("#purchaseProductInputName").val() == '')  &&  !($("#purchaseProductInputPrice").val() == '')  &&  !($("#purchaseProductInputQuantity").val() == '')  ){    
                purchaseInputSubmitFunction();
            }
        }

    });



    //                          indivisual submit button click area start 

        //done

    function printPurchaseTableData() {
        console.log('your current array is ');
        console.log(purchaseTableData);

        var html = '';
        $("#purchaseProductTableTbody").html(html);
        html = '';
        var i = 0;
        totalCost = 0;
        var productDiscountValue = 0;
        var productPurchaseTotal = 0;
        jQuery.each(purchaseTableData, function (row) {

            html += '<tr>'
            html += '<td>' + ++i + '</td>'
            html += '<td>' + purchaseTableData[row].id + '</td>'
            html += '<td>' + purchaseTableData[row].name + '</td>'
            html += '<td>' + parseFloat(purchaseTableData[row].price).toFixed( 2 ) + '</td>'
            html += '<td>' + purchaseTableData[row].quantity + '</td>'
            html += '<td>' + parseFloat(purchaseTableData[row].discountValue).toFixed( 2 ) + '</td>'
            html += '<td>' +parseFloat( purchaseTableData[row].total).toFixed( 2 ) + '</td>'
            html += '<td>'

            html += '<button type="button" productId=' + purchaseTableData[row].id + ' id="purchaseProductTableEdit" class="btn btn-success"> <i class="fa fa-edit" aria-hidden="false"> </i></button>'
            html += ' <button type="button" id="purchaseProductTableDelete" productId=' + purchaseTableData[row].id + '  class="btn btn-danger" > <i class="fa fa-trash" aria-hidden="false"> </i></button>'


            html += '</td> </tr>';

            productDiscountValue += purchaseTableData[row].discountValue;
            productPurchaseTotal += purchaseTableData[row].total;


        });
        
        $("#ProductDiscountTotal").text(parseFloat(productDiscountValue).toFixed(2));
        $("#productPurchaseTotal").val(parseFloat(productPurchaseTotal).toFixed(2));
        $("#purchaseProductTableTbody").html(html);
        calculatePurchaseFinal();
    }



    function initializePurchaseTableData(id) {
        purchaseTableData[id] = {
            id: id,
            name: 'name',
            price: 0,
            quantity: 0,
            discountType: 0,
            discount: 0,
            discountValue: 0,
            total: 0,
        };
    }


    //done
    function AddNewProductOnPruchaseCart() {

        // check if data is successfully loaded or not 
        var existingProduct = parseInt($("#productIdHidden").val().trim());
        var id = parseInt($("#purchaseProductInputId").val().trim());
        if (existingProduct == id) {
            // if loaded or modified
            var name = $("#purchaseProductInputName").val().trim();
            var price = $("#purchaseProductInputPrice").val().trim();
            var quantity = parseFloat($("#purchaseProductInputQuantity").val().trim());
            var discountType = $("#purchaseProductInputDiscountType").val().trim();
            var discount = $("#purchaseProductInputdiscount").val().trim();
            var discountValue = $("#purchaseProductInputDiscountValue").val().trim();
            var total = $("#purchaseProductInputTotal").val().trim();


        } else {
            // if not laoded 
            var name = databaseProducts[id]['name'];
            var price = databaseProducts[id]['price_per_unit'];
            var quantity = 1
            var discountType = $("#purchaseProductInputDiscountType").val().trim();
            var discount = 0;
            var discountValue = 0;
            var total = price;
        }





        if (typeof purchaseTableData[id] == 'undefined') {
            // console.log(id+ '   This index is not define yet ')
            initializePurchaseTableData(id)

        }

        purchaseTableData[id] = {
            id: id,
            name: name,
            price: price,
            quantity: parseFloat(purchaseTableData[id].quantity) + parseFloat(quantity),
            discountType: discountType,
            discount: discount,
            discountValue: parseFloat(purchaseTableData[id].discountValue) + parseFloat(discountValue),
            total: parseFloat(purchaseTableData[id].total) + parseFloat(total),
        };



        printPurchaseTableData();
        setIndivitualInputFieldDefault();
        $("#purchaseProductInputId").focus();
    }
    //done
    function updateProductOnPruchaseCart() {


        // check if data is successfully loaded or not 
        var existingProduct = parseInt($("#productIdHidden").val().trim());
        var id = parseInt($("#purchaseProductInputId").val().trim());
        if (existingProduct == id) {

            // if loaded or modified
            var name = $("#purchaseProductInputName").val().trim();
            var price = $("#purchaseProductInputPrice").val().trim();
            var quantity = parseFloat($("#purchaseProductInputQuantity").val().trim());
            var discountType = $("#purchaseProductInputDiscountType").val().trim();
            var discount = $("#purchaseProductInputdiscount").val().trim();
            var discountValue = $("#purchaseProductInputDiscountValue").val().trim();
            var total = $("#purchaseProductInputTotal").val().trim();


        } else {

            // if not laoded 
            var name = databaseProducts[id]['name'];
            var price = databaseProducts[id]['price_per_unit'];
            var quantity = 1
            var discountType = $("#purchaseProductInputDiscountType").val().trim();
            var discount = 0;
            var discountValue = 0;
            var total = price;
        }

        // var id = parseInt($("#purchaseProductInputId").val().trim());
        // var name = $("#purchaseProductInputName").val().trim();
        // var price = $("#purchaseProductInputPrice").val().trim();
        // var quantity = parseInt($("#purchaseProductInputQuantity").val().trim());
        // var discountType = $("#purchaseProductInputDiscountType").val().trim();
        // var discount = $("#purchaseProductInputdiscount").val().trim();
        // var discountValue = $("#purchaseProductInputDiscountValue").val().trim();
        // var total = $("#purchaseProductInputTotal").val().trim();






        purchaseTableData[id] = {
            id: id,
            name: name,
            price: price,
            quantity: parseFloat(quantity),
            discountType: discountType,
            discount: discount,
            discountValue: parseFloat(discountValue),
            total: parseFloat(total),
        };



        printPurchaseTableData();
        setIndivitualInputFieldDefault();
        $("#purchaseProductInputId").focus();

    };
    //done
    function purchaseInputSubmitFunction() {
        var submitButtonType = $("#purchaseProductInputSubmit").data("submit-type");
        var submitButtonProductId = $("#purchaseProductInputSubmit").data("item-id");
        var id = parseInt($("#purchaseProductInputId").val().trim());
        if (submitButtonType == 'update') {
            console.log("submitButtonType == 'update'");
            if (submitButtonProductId == id) {
                console.log('update method Called');
                updateProductOnPruchaseCart();
            } else {
                console.log('create method Called');
                AddNewProductOnPruchaseCart();
            }
        } else {
            console.log('create method Called');
            AddNewProductOnPruchaseCart();
        }


        $("#purchaseProductInputSubmit").data("submit-type", 'create');
        $("#purchaseProductInputSubmit").data("item-id", 0);
    }

    $("#purchaseProductInputSubmit").on("click", function () {
        purchaseInputSubmitFunction();


    });




    //                               ****************************************
    //                               ########## On Enter start #############
    //                               ****************************************

    ///// test 
    //done
    $("#purchaseProductInputId").keypress(function (e) {
        if (e.originalEvent.key === 'Enter' || e.originalEvent.keyCode === 13) {
            
            var product_id = parseInt($("#purchaseProductInputId").val().trim());

            if ( !(typeof databaseProducts[product_id] == 'undefined')) {
                console.log("enter is clicked")
                $("#productSuggession").html("");
                $("#productSuggession").hide();
                // purchaseInputSubmitFunction();
                $('#purchaseProductInputPrice').focus();
            }
        }

    });
    //                               *****************************************************************************
    //                               ##########  purchase product table delete button start here   #############
    //                               *******************************************************************************





    //done
    $("body").on("click", "#purchaseProductTableDelete", function () {
        var prooductId = $(this).attr('productId');
        console.log("Clicked On " + prooductId);
        delete purchaseTableData[prooductId];
        printPurchaseTableData();
        
        $("#purchaseProductInputPrice").focus();
    });


    //                               *****************************************************************************
    //                               ##########  purchase product table delete button start here   #############
    //                               *******************************************************************************

    //done
    $("body").on("click", "#purchaseProductTableEdit", function () {
        var prooductId = $(this).attr('productId');
        console.log("Clicked On update button " + prooductId);
        $("#purchaseProductInputSubmit").data("submit-type", 'update');
        $("#purchaseProductInputSubmit").data("item-id", prooductId);
        $("#purchaseProductInputSubmit").attr("disabled", false);


        var product = purchaseTableData[prooductId];

        $("#purchaseProductInputId").val(product.id);
        $("#productIdHidden").val(product.id);
        $("#purchaseProductInputName").val(product.name);
        $("#purchaseProductInputPrice").val(product.price);
        $("#purchaseProductInputdiscount").val(product.discount);
        $("#purchaseProductInputDiscountType").val(product.discountType);
        $("#purchaseProductInputDiscountValue").val(product.discountValue);
        $("#purchaseProductInputQuantity").val(product.quantity);
        $("#purchaseProductInputTotal").val(product.total);

        purchasePagePercentageInitialization(product.discountType);
        $("#purchaseProductError").hide();
        $("#purchaseProductInputSubmit").attr("disabled", false);

    });



    // done
    // Final caculation start 
    function calMoreDiscount() {
        var discountType = $("#productPurchaseMoreDiscountType").val().trim();
        var productPurchaseTotal = parseFloat($("#productPurchaseTotal").val().trim());
        var ProductDiscountTotal = parseFloat($("#ProductDiscountTotal").text().trim());
        var moreDiscountInput = parseFloat($("#moreDiscountInput").val().trim());

        if (discountType == 1) {
            var moreDiscountValue = (productPurchaseTotal * moreDiscountInput * 0.01);
            var totalDiscount = moreDiscountValue + ProductDiscountTotal;
            $("#discountTotal").val(totalDiscount.toFixed( 2 ));
            $("#totalDiscountInText").text(parseFloat(moreDiscountValue).toFixed( 2 ) + " + " + parseFloat(ProductDiscountTotal).toFixed( 2 ))
            return totalDiscount;
        } else {
            var totalDiscount = moreDiscountInput + ProductDiscountTotal;

            $("#discountTotal").val(totalDiscount.toFixed( 2 ));
            $("#totalDiscountInText").text(parseFloat(ProductDiscountTotal).toFixed( 2 ) + " + " + parseFloat(moreDiscountInput).toFixed( 2 ))
            return totalDiscount;
        }
    }
    //done
    function calSubTotal() {

        var ProductDiscountTotal = parseFloat($("#ProductDiscountTotal").text().trim());
        var productPurchaseTotal = parseFloat($("#productPurchaseTotal").val().trim());
        var discountTotal = parseFloat($("#discountTotal").val());
        var subTotal = parseFloat(productPurchaseTotal - discountTotal + ProductDiscountTotal);
        $("#purchaseSubtotal").text(subTotal.toFixed( 2 ));
        return subTotal;
    }


    // function calTax() {
    //     taxInput = $("#taxInput").val().trim();
    //     subTotal = $("#purchaseSubtotal").text().trim();
    //     tax = subTotal * taxInput * 0.01
    //     $("#taxValue").text(parseFloat(tax));

    // }

    //done
    function calTotal() {
        var previousDue = $("#supplierDue").text().trim();
        previousDue = (previousDue == "" ? 0 : previousDue);

        previousDue = parseFloat(previousDue);
        $("#purchasePreviousDue").text(previousDue.toFixed( 2 ));

        var subTotal = parseFloat($("#purchaseSubtotal").text().trim());
        var total = parseFloat(subTotal + previousDue);
        $("#totalWithOutDue").val(parseFloat(subTotal ).toFixed( 2 ));
        $("#finalTotal").text(total.toFixed( 2 ));
        $("#PayAmount").val(total.toFixed( 2 ));
        $("#totalDue").text(0);
    }



    //done
    function calculatePurchaseFinal() {
        $("#changeAmount").html('');

        calMoreDiscount();
        calSubTotal();
        // calTax();
        calTotal();

    }


    //done 
    $("#moreDiscountInput").on('input', function () {

        calculatePurchaseFinal();

        var subtotal = parseFloat(calSubTotal());
        console.log('SubTotal ' + subtotal);
        if (subtotal < 0) {
            $(this).val(0);

            calculatePurchaseFinal();
        }


        var data = $(this).val().trim();

        if (data.length < 1) {
            $(this).val(0);
            calculatePurchaseFinal();
        }
        if (data < 0) {
            $(this).val(0);
            calculatePurchaseFinal();
        }
    });
    // $("#moreDiscountInput").on('change', function () {
    //     calculatePurchaseFinal();

    //     var subtotal = parseFloat(calSubTotal());
    //     console.log('SubTotal ' + subtotal);
    //     if (subtotal < 0) {
    //         $(this).val(0);

    //         calculatePurchaseFinal();
    //     }

    // });




    //done
    $("#PayAmount").on('input', function () {
        $("#changeAmount").html('');
        var total = parseFloat($("#finalTotal").text().trim());

        var pay = $(this).val();

        if (pay == "") {
            $(this).val(0);
            pay = "0";
        }
        pay = parseFloat(pay);
        var due = total - pay;
        if (due <= 0) {
            $("#changeAmount").html("Exchange : " + (pay - total) + " TK");
            $("#totalDue").text(0);
        } else {
            $("#totalDue").text(due);
        }

    });


    // discount Area Start Here 

    //done
    // product discount start
    $("#disCountSetting").on('click', function () {

        $("#discountModal").modal();
    });
    //done
    function purchasePagePercentageInitialization(percentageType) {


        if (percentageType == 1) {
            $("#percentageIcon").show();
            $("#purchaseModalDiscountTypePercentage").attr('checked', true);
        } else {
            $("#percentageIcon").hide();
            $("#purchaseModalDiscountTypeFixed").attr('checked', true);
        }

        $("#purchaseProductInputDiscountType").val(percentageType);
    }
    purchasePagePercentageInitialization(1);

    //done
    $(".purchasePagePercentageSelect").on('click', function () {


        var data = $(this).val().trim();
        $("#purchaseProductInputDiscountType").val(data);
        if (data == 1) {
            $("#percentageIcon").show();
        } else {
            $("#percentageIcon").hide();
        }
        calIndivitualTotal();

    });


    // more discount  start
    //done
    $("#purchasePageMoreDiscountSetting").on('click', function () {

        $("#moreDiscountModal").modal();
    });
    //done
    function purchasePageMorePercentageInitialization(percentageType) {


        if (percentageType == 1) {
            $("#moreDiscountPercentageIcon").show();
            $("#purchaseMoreModalDiscountTypePercentage").attr('checked', true);
        } else {
            $("#moreDiscountPercentageIcon").hide();
            $("#purchaseMoreModalDiscountTypeFixed").attr('checked', true);
        }
        $("#productPurchaseMoreDiscountType").val(percentageType);
    }
    purchasePageMorePercentageInitialization(1);

    //done

    $(".purchasePageMoreDiscountSelect").on('click', function () {

        console.log('more discount Call')
        var data = $(this).val().trim();
        $("#productPurchaseMoreDiscountType").val(data);
        if (data == 1) {
            $("#moreDiscountPercentageIcon").show();
        } else {
            $("#moreDiscountPercentageIcon").hide();
        }

        calculatePurchaseFinal();

    });


    // // Tax Section start

    // $("#TaxSetting").on('click', function () {
    //     $("#taxModal").modal();
    // });

    // $("#taxInput").on('keyup', function () {
    //     var tax = $(this).val().trim();
    //     if (tax.length == 0) {

    //         $(this).val(0);
    //         $("#taxView").text(0);
    //     }
    //     if (tax >= 100) {
    //         $(this).val(0);
    //         $("#taxView").text(0);
    //     } else {
    //         $("#taxView").text(tax);
    //     }
    //     calculatePurchaseFinal();

    // });
    // $("#taxInput").on('change', function () {
    //     var tax = $(this).val().trim();
    //     if (tax.length == 0) {

    //         $(this).val(0);
    //         $("#taxView").text(0);
    //     }
    //     if (tax >= 100) {
    //         $(this).val(0);
    //         $("#taxView").text(0);
    //     } else {
    //         $("#taxView").text(parseFloat(tax));
    //     }
    //     calculatePurchaseFinal();

    // });









    //                               *****************************************************************************
    //                                           ##########  Purchase complete button start    #############
    //                               *******************************************************************************

    function cartIsEmpty(){
        var cardLegth =0;
        jQuery.each(purchaseTableData, function (row) {
            cardLegth++;
            return false;
        });
        if (cardLegth == 0) {
            return 1;
        }
        else{
            return 2
        }
    }
    
    $("#orderCompleteButton").attr("disabled", false);
        $("#orderCompleteButton").on('click', function () {
            
            $("#orderCompleteButton").attr("disabled", true);
            if (cartIsEmpty()==1) {
                alert('please add some Product');  
                
                $("#purchaseProductInputId").focus();   
                $("#orderCompleteButton").attr("disabled", false);
                    return;
            }
    
            // $("#orderCompleteButton").attr("disabled", false); /// onlly for testing , after teasting remove this 
    
            var purchaseData={
                supplier_id : $('#supplier_input_id').val(),
                payment_system_id : $("#paymentSystemId").val().trim(),
                paid_amount : $("#PayAmount").val().trim(),
                pre_due : $("#purchasePreviousDue").text().trim(),
                due : $("#totalDue").text().trim(),
                discount : $("#discountTotal").val().trim(),
                total : $("#totalWithOutDue").val().trim(),
    
            };
            console.log(purchaseData)
          
    
          
            var act = $("#homeRoute").val().trim()+'/purchases'; 
            var token = $("#csrfToken").val().trim();           
            console.log("---------- action " + act);
         
            $.ajax({
                type: 'post',
                url: act,
                data:{
                    "_token":token,
                    "purchase" :purchaseData,
                    "purchase_details":purchaseTableData
                },
                success: function (data) {
                    location.reload(true);
                    console.log(data);
    
                    // viewSupplierData(supplier);
                },
                error: function (data) {
                    alert("Failed order ..... Try Again !!!!!!!!!!!")
                    console.log('An error occurred.');
                    console.log(data);
                },
            });
    
    
    
            console.log(cartIsEmpty());
        });
    
    
    





















    //                               *****************************************************************************
    //                                           ##########  Search product suggession    #############
    //                               *******************************************************************************




    $("#productSuggession").hide();

    $("#purchaseProductInputId").on('keyup', function () {
        $("#productSuggession").show();

        var searchField = $("#purchaseProductInputId").val();
        var expression = new RegExp(searchField, "i");
        if (searchField.length == 0) {
            $("#productSuggession").hide();
            return false;
        }
        $("#productSuggession").html("");

        var count = 0;
        $.each(databaseProducts, function (key, value) {


            if (value.name.search(expression) != -1 || value.id == searchField) {
                if (count == 50) {
                    return false;
                }
                count++;
                $('#productSuggession').append(
                    '<a herf="#" class="list-group-item list-group-item-action border-1 searchItem text-dark" data-item-id="' +
                    value.id + '">' + +value.id + ' | ' + value.name + ' | ' + value
                    .price_per_unit + ' </a>')
            }

        });
        if (count == 0) {
            $('#productSuggession').html(
                '<div class="list-group-item list-group-item-action border-1 text-dark"> Not found any Data </div>'
            )
        }



    });







    $('body').click(function () {
        $("#productSuggession").hide();
        $("#productSuggession").html("");
    });

    $(document).on('click', '.searchItem', function () {
        var id = $(this).attr('data-item-id');
        $("#purchaseProductInputId").val(id)
        // alert(id);//this one needs to be triggered
        purchaseProductInputOnInput()
        $("#productSuggession").hide();
        $("#productSuggession").html("");
        $("#purchaseProductInputPrice").focus();
    });




});
