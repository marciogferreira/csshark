import Crud from '../../components/Crud';
import { ReactElement, useEffect, useState } from 'react';
import Api from '../../core/api';
import { Select } from '../../components/FormInputs';

type DataProps = {
  Field: ReactElement | any;
  ErrorMessage: ReactElement | any;
}
const FormWrapper = ({ Field, ErrorMessage }: DataProps) => {

    const[alunos, setAlunos] = useState([]);
    const[turmas, setTurmas] = useState([]);

    async function getAlunos() {
        const response = await Api.get('alunos/options');
        setAlunos(response.data.data);

        const responseT = await Api.get('turmas/options');
        setTurmas(responseT.data.data);
    }

    useEffect(() => {
        getAlunos();
    }, [])

   
    return (
        <>
            <div className='row'>
                <div className="col-md-12">
                   
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
                        <Field 
                            required
                            id="turma_id"
                            label="Turma"
                            name="turma_id"
                            placeholder="Selecione"
                            component={Select}
                            list={turmas}
                            error={<ErrorMessage name="turma_id" />}
                        />
                    </div> 

                    <div className='mb-3'>
                        <label>Data de Início</label>
                        <Field name="data_inicio" type="date" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="data_inicio" component="span" />
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
                        <label>Desconto</label>
                        <Field name="desconto" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="desconto"  className="error" />
                        </span>
                    </div>

                    <div className='mb-3'>
                        <label>Observação</label>
                        <Field name="observacao" type="text" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="observacao" component="span" />
                        </span>
                    </div>
                  
                </div>
                
            </div>           
        </>
    );
}

export default function AlunosTurmasIndex() {
    return (
        <Crud
            title="Matrículas"
            endPoint="alunos-turmas"
            searchFieldName='search'
            emptyObject={{
                observacao: '',
                aluno_id: '',
                turma_id: '',
                valor: '',
                desconto: '',
                data_inicio: '',
            }}
            fields={[
                { name: 'id', label: 'Id', classBody: 'min-width' },
                { name: 'name', label: 'Nome' },
                { name: 'name', label: 'Turma' },
                { name: 'name', label: 'Data Início' },
                { name: 'name', label: 'Ações' }
            ]}
            validation={(Yup: object | any) => {
                return {
                  aluno_id: Yup.string().required('Campo obrigatório'),
                  turma_id: Yup.string().required('Campo obrigatório'),
                  valor: Yup.string().required('Campo obrigatório'),
                  data_inicio: Yup.string().required('Campo obrigatório'),
                };
            }}
            fieldsHtml={({ item } : any) => (
                <>
                    
                    <td>{item.id}</td>
                    <td>{item.aluno.nome}</td>
                    <td>{item.turma.nome}</td>
                    <td>{item.data_inicio}</td>
                </>
            )}
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
