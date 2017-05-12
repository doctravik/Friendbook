<template>
    <div class="card">
        <div class="card__header">
            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
            <a class="card__title" :href="url"><strong v-text="title"></strong></a>
            <span>&bullet;</span>
            <span class="card__count" v-text="count"></span>
        </div> 
        <div class="card__body" v-if="users && users.length">
            <div class="user" v-for="user in users">
                <a class="user__link" :href="'/' + user.slug">
                    <img class="user__avatar" src="http://placehold.it/100x100">
                    <span class="user__name" v-text="getFullName(user)"></span>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                url: '#',
                title: '',
                users: null,
                count: 0,
                capacity: 9
            }
        },

        /**
         * Mounted event of the component.
         * 
         * @return void
         */
        mounted () {
            this.listenEvents();
        },

        methods: {
            /**
             * @param object user
             * @return void
             */
            addUser (user) {
                if (this.canBeAdded (user)) {
                    this.users.push(user);
                    this.count++;
                }
            },

            /**
             * @param object partner
             * @return void
             */
            deleteUser (partner) {
                this.users = this.users.filter(user => user.id != partner.id);
                this.count--;
            },

            /**
             * @return boolean
             */
            canBeAdded (user) {
                return this.hasEmptyRooms() && !this.hasUser(user);
            },

            /**
             * @param object partner
             * @return boolean
             */
            hasUser (partner) {
                return this.users.filter(user => user.id == partner.id).length;
            },

            /**
             * @return boolean
             */
            hasEmptyRooms () {
                return this.users.length < this.capacity;
            },

            /**
             * @param object user
             * @return string
             */
            getFullName (user) {
                return user.first_name + ' ' + user.last_name;
            },
        }
    }
</script>


<style scoped>
    .card {
        background-color: white;
        padding: 5px;
        border: 1px solid #CCC;
        border-radius: 4px;
        margin-bottom: 2em;
    }

    @media (min-width: 992px) {
        .card {
            max-width: 312px;
        }
    }

    .card__header {
        padding: 10px 7px;
    }
    
    .card__title {
        display: inline-block;
        font-size: 16px;
        margin-left: 8px;
    }

    .card__count {
        color: #888;
    }

    .card__body {
        display: flex;
        flex-wrap: wrap;  
    }

    .user {
        width: 33.3%;
        max-width: 100px;
        padding: 2px;
        margin: 0;
        position: relative;
    }

    .user__name {
        position: absolute;
        bottom: 10px;
        left: 10px;
        font-size: 14px;
        color: white;
    }

    .user__avatar {
        vertical-align: bottom;
        max-width: 100%;
    }

    .user__link {
        display: inline-block;
        padding: 0;
        margin: 0;
    }
</style>