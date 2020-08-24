
const AccessGuard = {
  methods: {
    $can: (permission) => {
      const route = permission.split('.')[0];
      const action = permission.split('.')[1];

      const storagePermissions = [].concat(JSON.parse(localStorage.getItem('user')).user.roles[0].permissions).concat(JSON.parse(localStorage.getItem('user')).user.permissions);
      const storagePermission = storagePermissions.filter(x => x.name.split('.')[0] == route).map(x => x.name.split('.')[1]);
      if (storagePermission.length != 0) {
        if (storagePermission.includes('*') || storagePermission.includes(action)) {
          return true;
        }
      }
      return false;
    }
  }
}


export default AccessGuard;
