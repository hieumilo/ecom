function recently($cookies) {

    if (!$cookies.get('rid')) {
        $cookies.put('rid', JSON.stringify([]));
    }

    function add(id) {
        var rid = [];
        if ($cookies.get('rid')) {
            rid = JSON.parse($cookies.get('rid'));
        }
        rid.push(id);
        $cookies.put('rid', JSON.stringify(rid));
    }

    return {
        add: function(id) {
            return add(id);
        }
    }
}
