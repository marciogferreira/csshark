import { useContext } from "react"
import Sidebar from "./SideBar"
import AuthContext from "../contexts/AuthContext"
import { Navigate } from "react-router-dom"
import types from "../types"
import SidebarAluno from "./SidebarAluno"

type DataProps = {
    children: React.ReactNode | null
}
export default function LayoutDefault({ children }: DataProps) {

    const { user, isLogged } = useContext(AuthContext)

    if(!isLogged) {
        return <Navigate to="/login" />
    }

    return(
        <>
            <div className="">
                {types.USER_ROLE_ADMIN == user.role ? <Sidebar /> : <SidebarAluno />}
            </div>
            <div className="container">
                {children}
            </div>
        </>
    )
}