/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const errorRoutes = {
  path: '404',
  component: () => import('@/views/error-page/404'),
  name: 'Page404',
  meta: { title: 'page404', noCache: true },
};

export default errorRoutes;
