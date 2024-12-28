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
                        <Field name="name" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="name" component="span" />
                        </span>
                    </div>
                    <div className='mb-3'>
                        <label>Email</label>
                        <Field name="email" type="email" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="email" component="span" />
                        </span>
                    </div>

                    <div className='mb-3'>
                        <label>Telefone</label>
                        <Field name="telefone" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="telefone"  className="error" />
                        </span>
                    </div>

                    <div className='mb-3'>
                        <label>WhatsApp</label>
                        <Field name="whatsapp" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="whatsapp"  className="error" />
                        </span>
                    </div>
                    <div className='mb-3'>
                        <label>Endereço</label>
                        <Field name="endereco" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="endereco"  className="error" />
                        </span>
                    </div>
                    
                </div>
                
            </div>           
        </>
    );
}

export default function ProfessoresIndex() {
    return (
        <Crud
            title="Professores"
            endPoint="colaborador"
            searchFieldName='search'
            emptyObject={{
                name: '',
                email: '',
                telefone: '',
                whatsapp: '',
                endereco: ''
            }}
            fields={[
                { name: 'id', label: 'Id', classBody: 'min-width' },
                { name: 'name', label: 'Nome' },
                { name: 'email', label: 'E-mail' }
            ]}
            validation={(Yup: object | any) => {
                return {
                  name: Yup.string().required('Campo obrigatório'),
                  email: Yup.string().required('Campo obrigatório'),
                  telefone: Yup.string().required('Campo obrigatório')
                };
            } }
            fieldsHtml={null}
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
