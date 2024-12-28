import Crud from '../../components/Crud';
import { ReactElement } from 'react';

type DataProps = {
  Field: ReactElement | any;
  ErrorMessage: ReactElement | any;
}
const FormWrapper = ({ Field, ErrorMessage }: DataProps) => {
   
    return (
        <>
            <div className='row'>
                <div className="col-md-12">
                    <div className='mb-3'>
                        <label>Nome</label>
                        <Field name="nome" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="nome" component="span" />
                        </span>
                    </div>                    
                </div>
                
            </div>           
        </>
    );
}

export default function ModalidadesIndex() {
    return (
        <Crud
            title="Modalidades"
            endPoint="modalidades"
            searchFieldName='search'
            emptyObject={{
                nome: '',
            }}
            fields={[
                { name: 'id', label: 'Id', classBody: 'min-width' },
                { name: 'nome', label: 'Nome' }
            ]}
            validation={(Yup: object | any) => {
                return {
                  nome: Yup.string().required('Campo obrigatório')
                };
            } }
            FormWrapper={FormWrapper} 
            columns={[]} 
            enableBtnNew={false} 
            saveContinueForm={false} 
            showActionsColumn={false} 
            showDeleteColumn={false} 
            showEditColumn={false} 
            showNewButton={false} 
            showSearch={false} 
            showPagination={false} 
            showSort={false}
            showTotal={false} 
            showViewHistory={false}
            showViewAlterStatus={false}        />
    );
}
