<script>
    import People from './People.vue';

    export default {
        props: ['followers', 'followersCount'],
        
        data () {
            return {
                url: '#',
                title: 'Followers',
                users: this.followers,
                count: this.followersCount
            }
        },

        methods: {
            /**
             * @return void
             */
            listenEvents () {
                eventDispatcher.$on('add-follower', user => {
                    this.addUser(user);
                });
            }
        },

        mixins: [ People ]
    }
</script>