angular.module('apps', [
        'adminctrl',
        'helper.service',
        'admin.service',
        'auth.service',
        'naif.base64',
        'swangular',
        'message.service'

    ])
    .controller('indexController', indexController);

function indexController($scope) {
    $scope.titleHeader = "Dinas Pariwisata Sorong";
    $scope.header = "";
    $scope.breadcrumb = "";
    $scope.title;
    $scope.$on("SendUp", function(evt, data) {
        $scope.header = data;
        $scope.header = data;
        $scope.breadcrumb = data;
        $scope.title = data;
        $.LoadingOverlay("hide");
    });
}