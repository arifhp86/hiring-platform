import HomePage from './pages/HomePage.vue';
import Candidates from './pages/Candidates.vue';

const routes = [
    { path: '/', component:  HomePage},
    { path: '/candidate-list', component: Candidates }
];

export default routes;