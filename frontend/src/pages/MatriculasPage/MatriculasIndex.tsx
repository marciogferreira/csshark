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
                        <label>Turno</label>
                        <Field name="turno" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="turno" component="span" />
                        </span>
                    </div>

                    <div className='mb-3'>
                        <label>Valor/Mensal</label>
                        <Field name="valor" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="valor" component="span" />
                        </span>
                    </div>

                    <div className='mb-3'>
                        <label>Modalidade</label>
                        <Field name="telefone" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="telefone"  className="error" />
                        </span>
                    </div>

                    <div className='mb-3'>
                        <label>Professor</label>
                        <Field name="whatsapp" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="whatsapp"  className="error" />
                        </span>
                    </div>
                  
                </div>
                
            </div>           
        </>
    );
}

export default function MatriculasIndex() {
    return (
        <Crud
            title="Matrículas"
            endPoint="matriculas"
            searchFieldName='search'
            emptyObject={{
                nome: '',
                turno: '',
                valor: '',
                colaborador_id: '',
                modalidade_id: '',
            }}
            fields={[
                { name: 'id', label: 'Id', classBody: 'min-width' },
                { name: 'name', label: 'Nome' }
            ]}
            validation={(Yup: object | any) => {
                return {
                  nome: Yup.string().required('Campo obrigatório'),
                  turno: Yup.string().required('Campo obrigatório'),
                  valor: Yup.string().required('Campo obrigatório'),
                  modalidade_id: Yup.string().required('Campo obrigatório'),
                  colaborador_id: Yup.string().required('Campo obrigatório'),
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
