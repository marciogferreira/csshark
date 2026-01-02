import { useContext, useState } from "react"
import AuthContext from "../contexts/AuthContext"
import { Navigate } from "react-router-dom"
import Menu from "./Menu"
import { IconButton } from "@material-tailwind/react"
import { Bars3Icon, XMarkIcon } from "@heroicons/react/16/solid"
import MenuDesktop from "./MenuDesktop"

type DataProps = {
    children: React.ReactNode | null
}
export default function LayoutDefault({ children }: DataProps) {
    const { isLogged, user } = useContext(AuthContext)
    const [isDrawerOpen, setIsDrawerOpen] = useState(false);
    const [open, setOpen] = useState(0);
    const [openAlert, setOpenAlert] = useState(true);
    const handleOpen = (value: any) => {
        setOpen(open === value ? 0 : value);
    };
    const openDrawer = () => setIsDrawerOpen(true);
    const closeDrawer = () => setIsDrawerOpen(false);


    if(!isLogged) {
        return <Navigate to="/login" />
    }

    return (
        <>
          <div className="wrapper">
            { window.innerWidth > 700 && user.role == 1 && 
              <div style={{ display: window.innerWidth > 700 ? 'block' : 'none' }} >
                <MenuDesktop />
              </div>
            }

            { user.role != 1 &&
              <header style={{  }} className="d-flex pl-3 items-center bg-blue-950">
                <div>
                    <>
                      <IconButton variant="text" size="lg" onClick={openDrawer} 
                        placeholder={undefined} onPointerEnterCapture={undefined} onPointerLeaveCapture={undefined}
                          onResize={undefined} onResizeCapture={undefined}>
                        <>
                          {isDrawerOpen ? (
                            <XMarkIcon className="text-white h-8 w-8 stroke-2" />
                          ) : (
                            <Bars3Icon className="text-white h-8 w-8 stroke-2" />
                          )}
                        </>
                      </IconButton>
                    </>
                </div>
                <div className="text-white">
                  <strong>CS SHARK</strong>
                </div>
              </header>
            }

            <main className="p-4">
              {children}
            </main>

          </div>
  
          <Menu 
            isDrawerOpen={isDrawerOpen} 
            closeDrawer={closeDrawer} 
            setOpenAlert={setOpenAlert}
            openAlert={openAlert}
            handleOpen={handleOpen}
            open={open}
          />
        </>
      );
  
}