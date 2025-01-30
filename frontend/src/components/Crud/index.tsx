import { useEffect, useState } from 'react';
import { Button, Row, Col } from 'react-bootstrap';
import Pagination from '../Pagination';
import Form from '../Form';
import Grid from '../Grid';
import useDebounce from '../useDebounce';
import Message from '../../core/Message';
import Api from '../../core/api';

// type DataProps = {
//     fields: any,
//     validation: any,
//     FormWrapper: any,
//     endPoint: string,
//     searchFieldName: string,
//     emptyObject: object,
//     columns: Array<{ title: string, field: string }>,
//     title: string,
//     FormSearch: any,
//     fieldsHtml: any,
//     enableBtnNew: boolean,
//     saveContinueForm: boolean,
//     showActionsColumn: boolean ,
//     showDeleteColumn: boolean,
//     showEditColumn: boolean,
//     showNewButton: boolean,
//     showSearch: boolean,
//     showPagination: boolean,
//     showSort: boolean,
//     showTotal: boolean,
//     showViewHistory: boolean,
//     showViewAlterStatus: boolean
// }
export default function Crud(props: any) {   

    const [view, setView] = useState('list');
    const [data, setData] = useState(props.emptyObject);
    const [list, setList] = useState([]);
    // const [search, setSearch] = useState('');
    const search = '';
    const [paramsSearch, setParamsSearch] = useState({});
    const [pagination, setPagination] = useState({});
    const [page, setPage] = useState(1);
    // const debouncedSearchTerm = useDebounce(search, 500);
    const debouncedSearchParamsTerm = useDebounce(paramsSearch, 500);
    

    async function loadData() {
        let params = {
            page: page,
            ...paramsSearch
        };
        
        if(props.searchFieldName && search) {
            // params[props.searchFieldName] = search;
        }
        const response = await Api.get(props.endPoint, {
            params: { ...params}
        });
        setList(response.data.data);
        setPagination(response.data);
    }
    
    async function handleSubmit (values: any) {
        let msg = 'Registro Salvo com Sucesso';

        if(values.id) {
            msg = 'Registro Atualizado com Sucesso';
             await Api.put(`${props.endPoint}/${values.id}`, values);
        } else {
            const response = await Api.post(props.endPoint, values);
            setData(response.data.data)
        }
        // objects.setSubmi
        Message.success(msg);
        loadData();
        if(!props.saveContinueForm) {
            setView('list');
        } else {
            setView('edit');
        }
        // setView('list');
    }

    function handleNew() {
        setData(props.emptyObject);
        setView('new');
    }

    async function handleEdit(item: any) {
        const response = await Api.get(`${props.endPoint}/${item.id}`);
        setData({...response.data.data});
        setView('edit');
    }

    async function handleDelete(item: any) {
        await Message.confirmation("Deseja deletar este registro?", async () => {
            await Api.delete(`${props.endPoint}/${item.id}`);
            loadData();
            Message.success("Registro deletado com sucesso.");
        }); 
    }

    function handleList(item: any) {
        setView('list');
        console.log(item)
        
    }

   

    useEffect(() => {
        loadData();
    }, [page]);

    useEffect(() => {
        if(search === '') {
            loadData();
        }
    }, [search]);

    // useEffect(() => {
    //     if(debouncedSearchTerm) {
    //         loadData();
    //     }
    // }, [debouncedSearchTerm]);

    useEffect(() => {
        if(debouncedSearchParamsTerm) {
            loadData();
        }
    }, [debouncedSearchParamsTerm]);
    

    return (
        <>
            
                <br />
                <h4>{props.title}</h4>
                <hr />
                
                    {view === 'list' ?
                        <Row>
                            <Col>
                                {/* <InputSearch 
                                    value={search} 
                                    handleText={value => setSearch(value)} 
                                    loadData={loadData}
                                /> */}
                            </Col>
                            <Col>
                                {!props.enableBtnNew && <Button size="sm" style={{ float: 'right', marginBottom: '20px' }} variant="success" onClick={handleNew}>Novo</Button>}
                            </Col>
                        </Row>
                        : null
                    }
                    {view === 'list' && 
                        <>
                            {props.FormSearch && <props.FormSearch params={paramsSearch} setParams={setParamsSearch}  />}
                            <Grid 
                                titleBtnEdit={''} 
                                enableBtnEdit={false} 
                                enableBtnDelete={false} 
                                handleNew={function (): void {
                                    throw new Error('Function not implemented.');
                                } } {...props}
                                list={list}
                                handleEdit={handleEdit}
                                handleDelete={handleDelete}
                            />
                            <Pagination
                                data={pagination}                            
                                onChange={(page: any) => setPage(page)} 
                            />
                        </>
                    }
                    {view === 'new' || view === 'edit' ? 
                        <Form 
                            {...props}
                            view={view}
                            emptyObject={data} 
                            handleSubmit={handleSubmit}
                            handleCancel={handleList}
                            handleEdit={handleEdit}
                            loadData={loadData}
                        /> : null}
                
                {/* <Card.Footer>
                    Footer
                </Card.Footer> */}
            
        </>
    );

}
