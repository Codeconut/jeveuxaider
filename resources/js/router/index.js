import Vue from "vue";
// import store from "../store";

import Router from "vue-router";

/* Layouts */
import Layout2Cols from "@/layout/Layout2Cols";
import LayoutRegisterSteps from "@/layout/LayoutRegisterSteps";
import LayoutSNU from "@/layout/LayoutSNU";

/* Pages */
import Login from "@/views/Login.vue";
import Logout from "@/views/Logout.vue";
import Register from "@/views/Register.vue";
import RegisterResponsable from "@/views/RegisterResponsable.vue";
import RegisterVolontaire from "@/views/RegisterVolontaire.vue";
import PasswordForgot from "@/views/PasswordForgot.vue";
import PasswordReset from "@/views/PasswordReset.vue";
import NotFound from "@/views/NotFound.vue";
import Forbidden from "@/views/Forbidden.vue";
import Maintenance from "@/views/Maintenance.vue";
import BrowserOutdated from "@/views/BrowserOutdated.vue";

// import Releases from "@/views/Releases.vue";

import FrontHomepage from "@/views/Front/Homepage";
import FrontAbout from "@/views/Front/About";
import FrontSecurityRules from "@/views/Front/SecurityRules";
import FrontMissions from "@/views/Front/Missions";
import FrontProfile from "@/views/Front/Profile";
import FrontSettings from "@/views/Front/Settings";

// Fix for NavigationDuplicated error -> need to add catch to push promise.
const originalPush = Router.prototype.push;
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err);
};

Vue.use(Router);

export default new Router({
  mode: "history",
  base: process.env.BASE_URL,
  routes: [
    {
      path: "/",
      name: "Homepage",
      component: FrontHomepage,
    },
    {
      path: "/regles-de-securite",
      name: "SecurityRules",
      component: FrontSecurityRules,
    },
    {
      path: "/a-propos",
      name: "About",
      component: FrontAbout,
    },
    {
      path: "/user",
      component: Layout2Cols,
      redirect: "/login",
      children: [
        {
          path: "/maintenance",
          name: "Maintenance",
          component: Maintenance,
        },
        {
          path: "/browser-outdated",
          name: "BrowserOutdated",
          component: BrowserOutdated
        },
        {
          path: "/login",
          name: "Login",
          component: Login,
          meta: { requiresAnonymous: true }
        },
        {
          path: "/register",
          name: "Register",
          component: Register,
          meta: { requiresAnonymous: true }
        },
        {
          path: "/register/volontaire",
          name: "RegisterVolontaire",
          component: RegisterVolontaire,
          meta: { requiresAnonymous: true }
        },
        {
          path: "/register/responsable",
          name: "RegisterResponsable",
          component: RegisterResponsable,
          meta: { requiresAnonymous: true }
        },
        {
          path: "/password/forgot",
          name: "PasswordForgot",
          component: PasswordForgot,
          meta: { requiresAnonymous: true }
        },
        {
          path: "/password/reset/:token",
          name: "PasswordReset",
          component: PasswordReset,
          meta: { requiresAnonymous: true }
        },
        {
          path: "/logout",
          name: "Logout",
          component: Logout
        },
      ]
    },
    {
      path: "/register/step",
      component: LayoutRegisterSteps,
      redirect: "/register/step/profile",
      meta: { requiresAuth: true },
      children: [
        {
          path: "/register/step/norole",
          component: () =>
            import(
              /* webpackChunkName: "assets/js/no-role-step" */ "@/views/RegisterSteps/NoRoleStep.vue"
            ),
          name: "NoRoleStep"
        },
        {
          path: "/register/step/profile",
          component: () =>
            import(
              /* webpackChunkName: "assets/js/profile-step" */ "@/views/RegisterSteps/ProfileStep.vue"
            ),
          name: "ProfileStep"
        },
        {
          path: "/register/step/structure",
          component: () =>
            import(
              /* webpackChunkName: "assets/js/structure-step" */ "@/views/RegisterSteps/StructureStep.vue"
            ),
          name: "StructureStep"
        },

        {
          path: "/register/step/address",
          component: () =>
            import(
              /* webpackChunkName: "assets/js/address-step" */ "@/views/RegisterSteps/AddressStep.vue"
            ),
          name: "AddressStep"
        },
        {
          path: "/register/step/other",
          component: () =>
            import(
                      /* webpackChunkName: "assets/js/no-role-step" */ "@/views/RegisterSteps/OtherStep.vue"
            ),
          name: "OtherStep"
        },
      ]
    },
    {
        path: "/dashboard",
        component: LayoutSNU,
        redirect: "/dashboard/missions",
        children: [
            {
                path: "/dashboard/missions",
                component: () =>
                  import(
                    /* webpackChunkName: "assets/js/dashboard-missions" */ "@/views/SNU/Missions.vue"
                  ),
                name: "DashboardMissions"
            },
            {
                path: "/dashboard/structure/:structureId/missions/add",
                component: () =>
                  import(/* webpackChunkName: "assets/js/dashboard-mission-add" */ "@/views/SNU/MissionForm.vue"),
                name: "MissionFormAdd",
                props: route => ({ structureId: parseInt(route.params.structureId) })
              },
              {
                path: "/dashboard/mission/:id/edit",
                component: () =>
                  import(/* webpackChunkName: "assets/js/dashboard-mission-edit" */ "@/views/SNU/MissionForm.vue"),
                name: "MissionFormEdit",
                props: route => ({ mode: "edit", id: parseInt(route.params.id) })
              },
              {
                path: "/dashboard/structures",
                component: () =>
                  import(
                    /* webpackChunkName: "assets/js/dashboard-structures" */ "@/views/SNU/Structures.vue"
                  ),
                name: "Structures"
              },
              {
                path: "/dashboard/structure/add",
                component: () =>
                  import(
                    /* webpackChunkName: "assets/js/dashboard-structure-add" */ "@/views/SNU/StructureForm.vue"
                  ),
                name: "StructureFormAdd",
                props: { mode: "add" },
                meta: {
                  roles: ["admin"]
                }
              },
              {
                path: "/dashboard/structure/:id",
                component: () =>
                  import(/* webpackChunkName: "assets/js/dashboard-structure-view" */ "@/views/SNU/Structure.vue"),
                name: "Structure",
                props: route => ({ id: parseInt(route.params.id) }),
                meta: {
                  roles: ["admin", "referent", "superviseur", "responsable"]
                }
              },
              {
                path: "/dashboard/structure/:id/edit",
                component: () =>
                  import(
                    /* webpackChunkName: "assets/js/dashboard-structure-edit" */ "@/views/SNU/StructureForm.vue"
                  ),
                name: "StructureFormEdit",
                props: route => ({ mode: "edit", id: parseInt(route.params.id) })
              },
              {
                path: "/dashboard/structure/:id/members",
                component: () =>
                  import(
                    /* webpackChunkName: "assets/js/dashboard-structure-members" */ "@/views/SNU/StructureMembers.vue"
                  ),
                name: "StructureMembers",
                props: route => ({ id: parseInt(route.params.id) })
              },
              {
                path: "/dashboard/structure/:id/members/add",
                component: () =>
                  import(
                    /* webpackChunkName: "assets/js/dashboard-structure-members-add" */ "@/views/SNU/StructureMembersAdd.vue"
                  ),
                name: "StructureMembersAdd",
                props: route => ({ id: parseInt(route.params.id) })
              },
              {
                path: "/dashboard/profiles",
                component: () =>
                  import(/* webpackChunkName: "assets/js/dashboard-profiles" */ "@/views/SNU/Profiles.vue"),
                name: "Profiles",
                meta: {
                  roles: ["admin", "referent", "superviseur"]
                }
              },
              {
                path: "/dashboard/participations",
                component: () =>
                  import(
                    /* webpackChunkName: "assets/js/dashboard-participations" */ "@/views/SNU/Participations.vue"
                  ),
                name: "DashboardParticipations"
            },
        ]
    },
    {
      path: '/user/profile',
      component: FrontProfile

    },
    {
      path: '/user/settings',
      component: FrontSettings

      },
    {
      path: '/missions',
      component: FrontMissions
    },
    {
      path: "/missions/:id",
      component: () =>
        import(
            /* webpackChunkName: "assets/js/mission" */ "@/views/Mission.vue"
        ),
      name: "Mission"
    },
    { path: "/403", component: Forbidden },
    { path: "*", component: NotFound }
  ]
});