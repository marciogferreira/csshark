import Swal from 'sweetalert';

const Message = {
    success: (msg: string) => {
        Swal({
            title: 'Sucesso',
            text: msg,
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    },
    error: (msg: string) => {
        Swal({
            title: 'Oops!',
            //text: msg,
            html: msg,
            icon: 'error',
            confirmButtonText: 'Ok'
        });
    },
    infor: (msg: string) => {
        Swal({
            title: 'Oops!',
            //text: msg,
            html: msg,
            icon: 'info',
            confirmButtonText: 'Ok'
        });
    },
    confirmation: async (msg: string, callBack: any) => {
        
        swal({
            title: "Atenção",
            text: msg,
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
                callBack();
            }
        });
    },
    validation: async (error: object) => {
        //const errors = error.response.data.errors;
        const errors = error.response.data.validation;
        //console.log(errors);
        // VALIDATION SYMFONY
        // errors.map(item => {
        //     Message.error(item);
        // });
        // VALIDATION LARAVEL
        
        Object.keys(errors).map(name => {
            if(errors[name].length) {
                Message.error(errors[name][0]);
            }
        });
    }
}

export default Message;