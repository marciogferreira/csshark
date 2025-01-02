import swal from 'sweetalert';

const Message = {
    success: (msg: string) => {
        swal("Sucesso", msg, "success");
    },
    error: (msg: string) => {
        swal("Oops!", msg, "error");
    },
    infor: (msg: string) => {
        swal("Oops!", msg, "info");
    },
    confirmation: async (msg: string, callBack: any) => {
        
        swal({
            title: "Atenção",
            text: msg,
            icon: "warning",
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
                callBack();
            }
        });
    },
    validation: async (error: any) => {
        //const errors = error.response.data.errors;
        const errors = error?.response.data.validation;
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