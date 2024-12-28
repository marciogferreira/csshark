import Crud from '../../components/Crud';
import { ReactElement, useEffect, useState } from 'react';
import { Select } from '../../components/FormInputs';
import Api from '../../core/api';

type DataProps = {
  Field: ReactElement | any;
  ErrorMessage: ReactElement | any;
}
const FormWrapper = ({ Field, ErrorMessage }: DataProps) => {
   
    const[alunos, setAlunos] = useState([]);

    async function getAlunos() {
        const response = await Api.get('alunos/options');
        setAlunos(response.data.data);
    }

    useEffect(() => {
        getAlunos();
    }, [])

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
                        <label>Observação</label>
                        <Field name="observacao" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="observacao" component="span" />
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
                        <Field 
                            required
                            id="aluno_id"
                            label="Aluno"
                            name="aluno_id"
                            placeholder="Selecione"
                            component={Select}
                            list={alunos}
                            error={<ErrorMessage name="aluno_id" />}
                        />
                    </div>                    

                    <div className='mb-3'>
                        <label>Desconto</label>
                        <Field name="desconto" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="desconto"  className="error" />
                        </span>
                    </div>
                  
                </div>
                
            </div>           
        </>
    );
}

export default function AlunosTreinosIndex() {
    return (
        <Crud
            title="Treinos de Alunos"
            endPoint="alunos-treinos"
            searchFieldName='search'
            emptyObject={{
                nome: '',
                observacao: '',
                aluno_id: '',
                turma_id: '',
                valor: '',
                desconto: '',
            }}
            fields={[
                { name: 'id', label: 'Id', classBody: 'min-width' },
                { name: 'name', label: 'Nome' }
            ]}
            validation={(Yup: object | any) => {
                return {
                  nome: Yup.string().required('Campo obrigatório'),
                  aluno_id: Yup.string().required('Campo obrigatório'),
                  turma_id: Yup.string().required('Campo obrigatório'),
                  valor: Yup.string().required('Campo obrigatório')
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
