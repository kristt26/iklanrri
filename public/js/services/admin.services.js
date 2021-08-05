angular.module('admin.service', [])
    .factory('dashboardServices', dashboardServices)
    .factory('wilayahServices', wilayahServices)
    .factory('wisataServices', wisataServices)
    .factory('umkmServices', umkmServices)
    .factory('usersServices', usersServices)
    .factory('galeryServices', galeryServices)
    .factory('kategoriServices', kategoriServices);


function dashboardServices($http, $q, StorageService, $state, helperServices, AuthService) {
    var controller = helperServices.url + 'users';
    var service = {};
    service.data = [];
    service.instance = false;
    return {
        get: get,
        post: post,
        put: put
    };

    function get() {
        var def = $q.defer();
        if (service.instance) {
            def.resolve(service.data);
        } else {
            $http({
                method: 'get',
                url: controller,
                headers: AuthService.getHeader()
            }).then(
                (res) => {
                    service.instance = true;
                    service.data = res.data;
                    def.resolve(res.data);
                },
                (err) => {
                    def.reject(err);
                }
            );
        }
        return def.promise;
    }

    function post(param) {
        var def = $q.defer();
        $http({
            method: 'post',
            url: helperServices.url + 'administrator/createuser/' + param.roles,
            data: param,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                service.data.push(res.data);
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
            }
        );
        return def.promise;
    }

    function put(param) {
        var def = $q.defer();
        $http({
            method: 'put',
            url: helperServices.url + 'administrator/updateuser/' + param.id,
            data: param,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                var data = service.data.find(x => x.id == param.id);
                if (data) {
                    data.firstName = param.firstName;
                    data.lastName = param.lastName;
                    data.userName = param.userName;
                    data.email = param.email;
                }
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
            }
        );
        return def.promise;
    }

}

function wilayahServices($http, $q, helperServices, AuthService, message) {
    var controller = helperServices.url + 'admin/wilayah/';
    var service = {};
    service.data = [];
    return {
        get: get,
        post: post,
        put: put,
        deleteKecamatan: deleteKecamatan,
        postKelurahan: postKelurahan,
        putKelurahan: putKelurahan,
        deleteKelurahan: deleteKelurahan
    };

    function get() {
        var def = $q.defer();
        $http({
            method: 'get',
            url: controller + 'get',
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                service.data = res.data;
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
                message.info(err.data);
            }
        );
        return def.promise;
    }

    function post(params) {
        var def = $q.defer();
        $http({
            method: 'post',
            url: controller + 'post',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
                message.info(err.data);
            }
        );
        return def.promise;
    }

    function put(params) {
        var def = $q.defer();
        $http({
            method: 'put',
            url: controller + 'put?id=' + params.id,
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
                message.info(err.data);
            }
        );
        return def.promise;
    }

    function deleteKecamatan(id) {
        var def = $q.defer();
        $http({
            method: 'delete',
            url: controller + 'delete?id=' + id,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
                message.info(err.data);
            }
        );
        return def.promise;
    }
    function postKelurahan(params) {
        var def = $q.defer();
        $http({
            method: 'post',
            url: controller + 'postKelurahan',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
                message.info(err.data);
            }
        );
        return def.promise;
    }

    function putKelurahan(params) {
        var def = $q.defer();
        $http({
            method: 'put',
            url: controller + '?id=' + params.id,
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
                message.info(err.data);
            }
        );
        return def.promise;
    }

    function deleteKelurahan(id) {
        var def = $q.defer();
        $http({
            method: 'delete',
            url: controller + '?id=' + id,
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
                message.info(err.data);
            }
        );
        return def.promise;
    }
}

function wisataServices($http, $q, helperServices, AuthService, message) {
    var controller = helperServices.url + 'admin/wisata/';
    var service = {};
    service.data = [];
    return {
        get: get,
        getId:getId,
        post: post,
        put: put
    };

    function get() {
        var def = $q.defer();
        $http({
            method: 'get',
            url: controller + 'get',
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                service.data = res.data;
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
            }
        );
        return def.promise;
    }

    function getId(id) {
        var def = $q.defer();
        $http({
            method: 'get',
            url: controller + 'getId/' + id,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
            }
        );
        return def.promise;
    }

    function post(params) {
        var def = $q.defer();
        $http({
            method: 'post',
            url: controller + 'post',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                // service.data.push(res.data);
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
                message.error(err.data);
            }
        );
        return def.promise;
    }

    function put(params) {
        var def = $q.defer();
        $http({
            method: 'put',
            url: controller + 'put',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
            }
        );
        return def.promise;
    }
}

function umkmServices($http, $q, helperServices, AuthService, message) {
    var controller = helperServices.url + 'admin/umkm/';
    var service = {};
    service.data = [];
    return {
        get: get,
        post: post,
        put: put
    };

    function get() {
        var def = $q.defer();
        $http({
            method: 'get',
            url: controller + 'get',
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                service.data = res.data;
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
            }
        );
        return def.promise;
    }

    function post(params) {
        var def = $q.defer();
        $http({
            method: 'post',
            url: controller + 'post',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                // service.data.push(res.data);
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
                message.error(err.data);
            }
        );
        return def.promise;
    }

    function put(params) {
        var def = $q.defer();
        $http({
            method: 'put',
            url: controller + 'put',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
            }
        );
        return def.promise;
    }
}

function usersServices($http, $q, helperServices, AuthService, message) {
    var controller = helperServices.url + 'admin/users/';
    var service = {};
    service.data = [];
    return {
        get: get,
        post: post,
        put: put
    };

    function get() {
        var def = $q.defer();
        $http({
            method: 'get',
            url: controller + 'get',
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                service.data = res.data;
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
            }
        );
        return def.promise;
    }

    function post(params) {
        var def = $q.defer();
        $http({
            method: 'post',
            url: controller + 'post',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                // service.data.push(res.data);
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
                message.error(err.data);
            }
        );
        return def.promise;
    }

    function put(params) {
        var def = $q.defer();
        $http({
            method: 'put',
            url: controller + '?id=' + params.id,
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                var data = service.data.find(x => x.id == params.id);
                if (data) {
                    data.kelengkapan = params.kelengkapan;
                    data.penjelasan = params.penjelasan;
                }
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
            }
        );
        return def.promise;
    }
}

function galeryServices($http, $q, helperServices, AuthService, message) {
    var controller = helperServices.url + 'admin/galery/';
    var service = {};
    service.data = [];
    return {
        get: get,
        post: post,
        put: put
    };

    function get(id) {
        var def = $q.defer();
        $http({
            method: 'get',
            url: controller + 'get?id=' + id,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                service.data = res.data;
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
            }
        );
        return def.promise;
    }

    function post(params) {
        var def = $q.defer();
        $http({
            method: 'post',
            url: controller + 'post',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                service.data.foto.push(res.data);
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
                message.error(err.data);
            }
        );
        return def.promise;
    }

    function put(params) {
        var def = $q.defer();
        $http({
            method: 'put',
            url: controller + '?id=' + params.id,
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                var data = service.data.find(x => x.id == params.id);
                if (data) {
                    data.kelengkapan = params.kelengkapan;
                    data.penjelasan = params.penjelasan;
                }
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
            }
        );
        return def.promise;
    }
}

function kategoriServices($http, $q, helperServices, AuthService, message) {
    var controller = helperServices.url + 'admin/kategori/';
    var service = {};
    service.data = [];
    return {
        get: get,
        post: post,
        put: put,
        deleted: deleted
    };

    function get(id) {
        var def = $q.defer();
        $http({
            method: 'get',
            url: controller + 'get',
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                service.data = res.data;
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
            }
        );
        return def.promise;
    }

    function post(params) {
        var def = $q.defer();
        $http({
            method: 'post',
            url: controller + 'post',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                service.data.push(res.data);
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err.data);
                message.error(err.data);
            }
        );
        return def.promise;
    }

    function put(params) {
        var def = $q.defer();
        $http({
            method: 'put',
            url: controller + 'put',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                var data = service.data.find(x => x.id == params.id);
                if (data) {
                    data.nama = params.nama;
                    data.warna = params.warna;
                }
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
            }
        );
        return def.promise;
    }

    function deleted(params) {
        var def = $q.defer();
        $http({
            method: 'delete',
            url: controller + 'delete/' + params.id,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                var index = service.data.indexOf(params)
                service.data.splice(index, 1);
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
            }
        );
        return def.promise;
    }
}