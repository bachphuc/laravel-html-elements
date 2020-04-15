var app = angular.module('myApp', [], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});
app.controller('myCtrl', function ($scope, $timeout, $http) {
    
});

app.controller('reprocessOrderCtrl', function ($scope, $timeout, $http, $interval) {
    $scope.processForm = {
        product : null,
        model : null,
        type : $('#reprocess_type').val()
    };

    $http.get(window.baseUrl + '/products/get/all').success(function(data){
        $scope.products = data;
    });

    $scope.onProductChange = function(){
        if(!$scope.processForm.product) return;
        $http.get(window.baseUrl + '/products/' + $scope.processForm.product.id + '/models/get/' + $scope.processForm.type).success(function(data){
            $scope.models = data;
        });
    }

    $scope.reprocessOrders = function(){
        if(!$scope.processForm.model) return;
        if(!$('#selected_order_ids').val()) return;
        if(!$('#reprocess_status').val()) return;
        $('#reprocess-orders-form').submit()
    }
});
