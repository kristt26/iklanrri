angular.module('admin.service', [])
    .factory('dashboardServices', dashboardServices)
    .factory('layananServices', layananServices)
    .factory('tarifServices', tarifServices)
    .factory('pasangIklanServices', pasangIklanServices)
    .factory('profileServices', profileServices)
    .factory('userServices', userServices)
    ;


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

function layananServices($http, $q, helperServices, AuthService, message) {
    var controller = helperServices.url + 'admin/layanan/';
    var service = {};
    service.data = [];
    return {
        get: get,
        post: post,
        put: put,
        deleted: deleted,
    };

    function get() {
        var def = $q.defer();
        $http({
            method: 'get',
            url: controller + 'read',
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
            url: controller + 'create',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                service.data.push(res.data);
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
            url: controller + 'update',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                var data = service.data.find(x => x.id == params.id);
                if (data) {
                    data.layanan = params.layanan;
                    data.status = params.status;
                }
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
                message.info(err.data);
            }
        );
        return def.promise;
    }

    function deleted(param) {
        var def = $q.defer();
        $http({
            method: 'delete',
            url: controller + 'delete/' + param.id,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                var index = service.data.indexOf(param);
                service.data.splice(index, 1);
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

function tarifServices($http, $q, helperServices, AuthService, message) {
    var controller = helperServices.url + 'admin/tarif/';
    var service = {};
    service.data = [];
    return {
        get: get,
        post: post,
        put: put,
        deleted: deleted,
    };

    function get() {
        var def = $q.defer();
        $http({
            method: 'get',
            url: controller + 'read',
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
            url: controller + 'create',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                var data = service.data.find(x => x.id == res.data.layananid);
                if (data) {
                    var item = data.kategori.find(x => x.kategori == res.data.kategori);
                    if (item) {
                        item.tarif.push(res.data);
                    }

                }
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
            url: controller + 'update',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                var data = service.data.find(x => x.id == params.id);
                if (data) {
                    data.layanan = params.layanan;
                    data.status = params.status;
                }
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
                message.info(err.data);
            }
        );
        return def.promise;
    }

    function deleted(param) {
        var def = $q.defer();
        $http({
            method: 'delete',
            url: controller + 'delete/' + param.id,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                var index = service.data.indexOf(param);
                service.data.splice(index, 1);
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
function pasangIklanServices($http, $q, helperServices, AuthService, message) {
    var controller = helperServices.url + 'iklan/';
    var service = {};
    service.data = [];
    return {
        get: get,
        post: post,
        put: put,
        deleted: deleted,
        cekStatus: cekStatus,
        getJadwal: getJadwal
    };

    function get() {
        var def = $q.defer();
        $http({
            method: 'get',
            url: controller + 'read',
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                service.data = res.data.iklan;
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
            url: controller + 'create',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                service.data.push(res.data.iklan);
                def.resolve(res.data.token);
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
            url: controller + 'update',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                var data = service.data.find(x => x.id == params.id);
                if (data) {
                    data.layanan = params.layanan;
                    data.status = params.status;
                }
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
                message.info(err.data);
            }
        );
        return def.promise;
    }

    function deleted(param) {
        var def = $q.defer();
        $http({
            method: 'delete',
            url: controller + 'delete/' + param.id,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                var index = service.data.indexOf(param);
                service.data.splice(index, 1);
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
                message.info(err.data);
            }
        );
        return def.promise;
    }

    function cekStatus(params) {
        var def = $q.defer();
        $http({
            method: 'put',
            url: controller + 'status',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                var data = service.data.find(x => x.id == params.id);
                if (data) {
                    data.layanan = params.layanan;
                    data.status = params.status;
                }
                def.resolve(res.data);
            },
            (err) => {
                def.reject(err);
                message.info(err.data);
            }
        );
        return def.promise;
    }

    function getJadwal(params) {
        var def = $q.defer();
        $http({
            method: 'post',
            url: controller + 'jumlahsiaran',
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
}

function profileServices($http, $q, helperServices, AuthService, message) {
    var controller = helperServices.url + 'profile/';
    var service = {};
    service.data = [];
    return {
        get: get,
        put: put,
    };

    function get() {
        var def = $q.defer();
        $http({
            method: 'get',
            url: controller + 'read',
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

    function put(params) {
        var def = $q.defer();
        $http({
            method: 'put',
            url: controller + 'update',
            data: params,
            headers: AuthService.getHeader()
        }).then(
            (res) => {
                service.data.fullname = params.fullname;
                service.data.kontak = params.kontak;
                service.data.alamat = params.alamat;
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

function userServices($http, $q, helperServices, AuthService, message) {
    var controller = helperServices.url + 'admin/users/';
    var service = {};
    service.data = [];
    return {
        get: get
    };

    function get() {
        var def = $q.defer();
        $http({
            method: 'get',
            url: controller + 'read',
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

    // function post(params) {
    //     var def = $q.defer();
    //     $http({
    //         method: 'put',
    //         url: controller + 'create',
    //         data: params,
    //         headers: AuthService.getHeader()
    //     }).then(
    //         (res) => {
    //             service.data = res.data;
    //             def.resolve(res.data);
    //         },
    //         (err) => {
    //             def.reject(err);
    //             message.info(err.data);
    //         }
    //     );
    //     return def.promise;
    // }

    // function put(params) {
    //     var def = $q.defer();
    //     $http({
    //         method: 'put',
    //         url: controller + 'update',
    //         data: params,
    //         headers: AuthService.getHeader()
    //     }).then(
    //         (res) => {
    //             def.resolve(res.data);
    //         },
    //         (err) => {
    //             def.reject(err);
    //             message.info(err.data);
    //         }
    //     );
    //     return def.promise;
    // }
}